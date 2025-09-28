<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\StudentFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function enrollment()
    {
        return view('enrollment');
    }

    public function submitEnrollment(Request $request)
    {
        // Support unified admin-style field names while accepting legacy public names
        $validator = Validator::make($request->all(), [
            // Identity
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'mi' => 'nullable|string|max:10',
            'email' => 'required|email|max:255',
            'contact_number' => 'required|string|max:20',
            // Academics
            'school_year' => 'nullable|string|max:20',
            'lrn_number' => 'nullable|string|max:40|unique:students,lrn_number',
            'grade' => 'required_without:grade_level|string|max:20',
            'grade_level' => 'required_without:grade|integer|between:1,12',
            // Demographics
            'sex' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'religion' => 'required|string|max:100',
            'current_address' => 'required|string|max:500',
            // Parents/Guardian
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'parent_email' => 'nullable|email',
            'relationship' => 'required|in:father,mother,guardian,other',
            'father_firstname' => 'nullable|string|max:100',
            'father_lastname' => 'nullable|string|max:100',
            'father_mi' => 'nullable|string|max:10',
            'mother_firstname' => 'nullable|string|max:100',
            'mother_lastname' => 'nullable|string|max:100',
            'mother_mi' => 'nullable|string|max:10',
            'guardian_firstname' => 'nullable|string|max:100',
            'guardian_lastname' => 'nullable|string|max:100',
            'guardian_mi' => 'nullable|string|max:10',
            // Special
            'pwd' => 'nullable|boolean',
            'pwd_details' => 'nullable|string|max:200',
            // Academics extra
            'previous_school' => 'nullable|string|max:255',
            // Consent
            'terms' => 'sometimes|accepted',
            // Optional password setup (if provided now)
            'password' => 'nullable|string|min:8|confirmed',
            // Required documents (optional at submission, but tracked)
            'birth_certificate' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'report_card' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $student = DB::transaction(function () use ($request) {
                // Find or create user
                $fullName = trim($request->input('firstname') . ' ' . ($request->input('mi') ? $request->input('mi') . ' ' : '') . $request->input('lastname'));

                $existing = User::where('email', $request->input('email'))->first();
                if ($existing && $existing->role !== 'student') {
                    abort(422, 'This email is already used by a non-student account. Please use a different email.');
                }

                $user = User::firstOrCreate(
                    ['email' => $request->input('email')],
                    [
                        'name' => $fullName,
                        'password' => Hash::make($request->input('password') ?: bin2hex(random_bytes(16))),
                        'role' => 'student',
                        'status' => 'active',
                    ]
                );

                // If user already linked to a student, block
                if ($user->student && $user->student->exists) {
                    abort(422, 'This email is already linked to another student record.');
                }

                // Generate unified APP id
                do {
                    $applicationId = 'APP' . date('Y') . '-' . str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                } while (Student::where('application_id', $applicationId)->exists());

                $schoolYear = $request->input('school_year') ?: (date('n') >= 6
                    ? date('Y') . '-' . (date('Y') + 1)
                    : (date('Y') - 1) . '-' . date('Y'));

                $payload = [
                    'application_id' => $applicationId,
                    'school_year' => $schoolYear,
                    'lrn_number' => $request->input('lrn_number'),
                    'firstname' => $request->input('firstname'),
                    'lastname' => $request->input('lastname'),
                    'mi' => $request->input('mi'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('contact_number'),
                    'contact_number' => $request->input('contact_number'),
                    'sex' => $request->input('sex'),
                    'date_of_birth' => $request->input('date_of_birth'),
                    'religion' => $request->input('religion'),
                    'grade' => $request->input('grade'),
                    'grade_level' => $request->input('grade'), // Map grade to grade_level
                    'current_address' => $request->input('current_address'),
                    'pwd' => (bool) $request->boolean('pwd', false),
                    'pwd_details' => $request->input('pwd_details'),
                    'parent_name' => $request->input('parent_name'),
                    'parent_phone' => $request->input('parent_phone'),
                    'parent_email' => $request->input('parent_email'),
                    'relationship' => $request->input('relationship'),
                    'father_firstname' => $request->input('father_firstname'),
                    'father_lastname' => $request->input('father_lastname'),
                    'father_mi' => $request->input('father_mi'),
                    'mother_firstname' => $request->input('mother_firstname'),
                    'mother_lastname' => $request->input('mother_lastname'),
                    'mother_mi' => $request->input('mother_mi'),
                    'guardian_firstname' => $request->input('guardian_firstname'),
                    'guardian_lastname' => $request->input('guardian_lastname'),
                    'guardian_mi' => $request->input('guardian_mi'),
                    'previous_school' => $request->input('previous_school'),
                    'medical_conditions' => $request->input('medical_conditions'),
                    'additional_notes' => $request->input('additional_notes'),
                    'status' => 'submitted',
                    'user_id' => $user->id,
                ];

                $student = Student::create($payload);

                // Handle uploads for required documents (2 items)
                $requiredDocuments = [
                    'birth_certificate' => 'Birth Certificate',
                    'report_card' => 'Report Card/Form 138',
                ];

                foreach ($requiredDocuments as $docKey => $docLabel) {
                    if ($request->hasFile($docKey)) {
                        $file = $request->file($docKey);
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
                        // Track missing documents to surface in admin Files page
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

                // For testing environments, skip mail and mark as verified + set a default password if missing
                $requireVerification = (bool) env('REQUIRE_EMAIL_VERIFICATION', app()->environment('production'));
                if (!$requireVerification) {
                    // Auto-verify and ensure password exists for login
                    if (is_null($user->email_verified_at)) {
                        $user->forceFill(['email_verified_at' => now()])->save();
                    }
                } else {
                    // If no password was provided, send a set-password link
                    if (!$request->filled('password')) {
                        try {
                            // Use Laravel's standard password reset flow as set-password
                            \Illuminate\Support\Facades\Password::sendResetLink(['email' => $user->email]);
                        } catch (\Throwable $e) {
                            // swallow; mail may be disabled in local
                        }
                    }

                    // Send verification email if needed
                    try {
                        if (method_exists($user, 'sendEmailVerificationNotification')) {
                            $user->sendEmailVerificationNotification();
                        }
                    } catch (\Throwable $e) {
                        // ignore mail failures in dev
                    }
                }

                return $student;
            });

            return redirect()->route('check-status')->with('success',
                "Application submitted successfully! Your Application ID is: {$student->application_id}. Please check your email to set your password and verify your account.");

        } catch (\Throwable $e) {
            Log::error('Enrollment submission failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['error' => 'Failed to submit application. Please try again.'])->withInput();
        }
    }

    public function checkStatus()
    {
        return view('check-status');
    }

    public function processStatusCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'application_id' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $application = null;

        if ($request->application_id) {
            $application = Student::where('application_id', $request->application_id)->first();
        } elseif ($request->email && $request->phone) {
            $application = Student::where('email', $request->email)
                                 ->where('phone', $request->phone)
                                 ->first();
        }

        if (!$application) {
            return view('check-status', ['error' => 'No application found with the provided information.']);
        }

        return view('check-status', ['application' => $application]);
    }

    public function showRegistration()
    {
        return view('register');
    }

    public function processRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            // Public registration must NOT allow teacher accounts
            'role' => 'required|in:admin,staff',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'employee_id' => 'nullable|string|max:50|unique:users,employee_id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Generate employee ID if not provided
            $employeeId = $request->employee_id ?: strtoupper($request->role[0]) . date('Y') . str_pad(User::count() + 1, 4, '0', STR_PAD_LEFT);
            
            // Final guard: do not allow teacher creation via this endpoint even if bypassing UI
            if ($request->role === 'teacher') {
                abort(403, 'Teacher accounts can only be created by administrators.');
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'phone' => $request->phone,
                'department' => $request->department,
                'employee_id' => $employeeId,
                'status' => 'active',
            ]);

            return redirect()->route('login')->with('success', 
                "Account created successfully! Your Employee ID is: {$employeeId}. You can now login with your credentials.");
                
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create account. Please try again.')->withInput();
        }
    }
}