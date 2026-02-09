<?php

namespace App\Services;

use App\Models\User;
use App\Models\Student;
use App\Models\StudentFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;

class EnrollmentService
{
    /**
     * Process a new student enrollment
     *
     * @param array $data Validated enrollment data
     * @return Student
     */
    public function processEnrollment(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Create or find user account
            $fullName = trim($data['firstname'] . ' ' . ($data['mi'] ? $data['mi'] . ' ' : '') . $data['lastname']);
            
            $existing = User::where('email', $data['email'])->first();
            if ($existing && $existing->role !== 'student') {
                abort(422, 'This email is already used by a non-student account. Please use a different email.');
            }
            
            // Create user with secure password handling
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $fullName,
                    'password' => isset($data['password']) 
                        ? Hash::make($data['password']) 
                        : Hash::make(Str::random(32)), // Temporary password that will be reset
                    'role' => 'student',
                    'status' => 'active',
                ]
            );
            
            // If user already linked to a student, block
            if ($user->student && $user->student->exists) {
                abort(422, 'This email is already linked to another student record.');
            }
            
            // Generate application ID
            $applicationId = $this->generateApplicationId();
            
            // Set school year if not provided
            $schoolYear = $data['school_year'] ?? $this->getCurrentSchoolYear();
            
            // Create student record
            $student = Student::create([
                'application_id' => $applicationId,
                'school_year' => $schoolYear,
                'lrn_number' => $data['lrn_number'] ?? null,
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'mi' => $data['mi'] ?? null,
                'email' => $data['email'],
                'phone' => $data['contact_number'],
                'contact_number' => $data['contact_number'],
                'sex' => $data['sex'],
                'date_of_birth' => $data['date_of_birth'],
                'religion' => $data['religion'],
                'grade' => $data['grade'] ?? $data['grade_level'],
                'grade_level' => $data['grade_level'] ?? $data['grade'],
                'current_address' => $data['current_address'],
                'pwd' => (bool) ($data['pwd'] ?? false),
                'pwd_details' => $data['pwd_details'] ?? null,
                'parent_name' => $data['parent_name'],
                'parent_phone' => $data['parent_phone'],
                'parent_email' => $data['parent_email'] ?? null,
                'relationship' => $data['relationship'],
                'father_firstname' => $data['father_firstname'] ?? null,
                'father_lastname' => $data['father_lastname'] ?? null,
                'father_mi' => $data['father_mi'] ?? null,
                'mother_firstname' => $data['mother_firstname'] ?? null,
                'mother_lastname' => $data['mother_lastname'] ?? null,
                'mother_mi' => $data['mother_mi'] ?? null,
                'guardian_firstname' => $data['guardian_firstname'] ?? null,
                'guardian_lastname' => $data['guardian_lastname'] ?? null,
                'guardian_mi' => $data['guardian_mi'] ?? null,
                'previous_school' => $data['previous_school'] ?? null,
                'medical_conditions' => $data['medical_conditions'] ?? null,
                'additional_notes' => $data['additional_notes'] ?? null,
                'status' => 'submitted',
                'user_id' => $user->id,
            ]);
            
            // Process required documents
            $this->processRequiredDocuments($student, $data);
            
            // Handle email verification based on environment
            $this->handleEmailVerification($user, $data);
            
            return $student;
        });
    }
    
    /**
     * Generate a unique application ID
     *
     * @return string
     */
    protected function generateApplicationId(): string
    {
        do {
            $applicationId = 'APP' . date('Y') . '-' . str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (Student::where('application_id', $applicationId)->exists());
        
        return $applicationId;
    }
    
    /**
     * Get the current school year
     *
     * @return string
     */
    protected function getCurrentSchoolYear(): string
    {
        return date('n') >= 6
            ? date('Y') . '-' . (date('Y') + 1)
            : (date('Y') - 1) . '-' . date('Y');
    }
    
    /**
     * Process required documents for enrollment
     *
     * @param Student $student
     * @param array $data
     * @return void
     */
    protected function processRequiredDocuments(Student $student, array $data): void
    {
        $requiredDocuments = [
            'birth_certificate' => 'Birth Certificate',
            'report_card' => 'Report Card/Form 138',
        ];
        
        foreach ($requiredDocuments as $docKey => $docLabel) {
            if (isset($data[$docKey]) && $data[$docKey]) {
                $file = $data[$docKey];
                $storedPath = $file->store("student-files/{$student->id}", 'public');
                
                StudentFile::create([
                    'student_id' => $student->id,
                    'file_type' => $docKey,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $storedPath,
                    'file_size' => (string) $file->getSize(),
                    'mime_type' => $file->getClientMimeType(),
                    'status' => 'pending',
                    'notes' => null,
                    'uploaded_at' => now(),
                ]);
            } else {
                // Track missing documents
                StudentFile::create([
                    'student_id' => $student->id,
                    'file_type' => $docKey,
                    'file_name' => '',
                    'file_path' => '',
                    'file_size' => null,
                    'mime_type' => null,
                    'status' => 'missing',
                    'notes' => 'Not uploaded during enrollment submission.',
                    'uploaded_at' => null,
                ]);
            }
        }
    }
    
    /**
     * Handle email verification based on environment
     *
     * @param User $user
     * @param array $data
     * @return void
     */
    protected function handleEmailVerification(User $user, array $data): void
    {
        $requireVerification = (bool) env('REQUIRE_EMAIL_VERIFICATION', app()->environment('production'));
        
        if (!$requireVerification) {
            // Auto-verify in non-production environments
            if (is_null($user->email_verified_at)) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }
        } else {
            // Send password reset link if no password was provided
            if (!isset($data['password'])) {
                try {
                    Password::sendResetLink(['email' => $user->email]);
                } catch (\Throwable $e) {
                    Log::error('Failed to send password reset link', [
                        'email' => $user->email,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            // Send verification email
            try {
                if (method_exists($user, 'sendEmailVerificationNotification')) {
                    $user->sendEmailVerificationNotification();
                }
            } catch (\Throwable $e) {
                Log::error('Failed to send verification email', [
                    'email' => $user->email,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
    
    /**
     * Check enrollment status
     *
     * @param string|null $applicationId
     * @param string|null $email
     * @param string|null $phone
     * @return Student|null
     */
    public function checkEnrollmentStatus(?string $applicationId, ?string $email, ?string $phone): ?Student
    {
        if ($applicationId) {
            return Student::where('application_id', $applicationId)->first();
        } elseif ($email && $phone) {
            return Student::where('email', $email)
                         ->where('phone', $phone)
                         ->first();
        }
        
        return null;
    }
}
