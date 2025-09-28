<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('lrn_number', 'like', "%{$search}%")
                  ->orWhere('application_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('grade')) {
            $query->where(function($q) use ($request) {
                $q->where('grade', $request->grade)
                  ->orWhere('grade_level', $request->grade);
            });
        }

        $students = $query->orderBy('created_at', 'desc')->paginate(15);

        if ($request->expectsJson()) {
            // Compute stats on the full filtered dataset (not just current page)
            $statsQuery = clone $query;
            $stats = [
                'total' => (clone $statsQuery)->count(),
                'active' => (clone $statsQuery)->where('status', 'Active')->count(),
                'enrolled' => (clone $statsQuery)->where('status', 'enrolled')->count(),
                'pending' => (clone $statsQuery)->where('status', 'pending')->count(),
                'graduated' => (clone $statsQuery)->where('status', 'graduated')->count(),
            ];
            
            $pagination = [
                'total' => $students->total(),
                'from' => $students->firstItem(),
                'to' => $students->lastItem(),
                'currentPage' => $students->currentPage(),
                'lastPage' => $students->lastPage()
            ];
            
            return response()->json([
                'success' => true,
                'students' => $students->items(),
                'stats' => $stats,
                'pagination' => $pagination
            ]);
        }

        // If this is the teacher area, render the teacher-specific view (the page loads data via AJAX)
        if ($request->routeIs('teacher.*')) {
            return view('teacher.students.index');
        }

        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function show(Student $student)
    {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'student' => $student->load('user')
            ]);
        }

        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        // If it's an AJAX/JSON request from teacher UI, allow partial updates with relaxed validation
        if ($request->expectsJson()) {
            $data = $request->validate([
                'first_name' => 'sometimes|string|max:100',
                'lastname' => 'sometimes|string|max:100',
                'firstname' => 'sometimes|string|max:100',
                'last_name' => 'sometimes|string|max:100',
                'email' => 'sometimes|email',
                'contact_number' => 'sometimes|string|max:20',
                'grade' => 'sometimes|string|max:20',
                'grade_level' => 'sometimes|string|max:20',
                'status' => 'sometimes|in:Active,enrolled,inactive,graduated,pending,missing_docs,submitted',
            ]);

            try {
                // Map possible field names
                $mapped = $data;
                if (isset($data['first_name'])) { $mapped['firstname'] = $data['first_name']; unset($mapped['first_name']); }
                if (isset($data['last_name'])) { $mapped['lastname'] = $data['last_name']; unset($mapped['last_name']); }

                // Ensure both grade_level and grade fields are updated for backward compatibility
                if (isset($data['grade_level'])) {
                    $mapped['grade'] = 'Grade ' . $data['grade_level'];
                }

                $student->fill($mapped);

                // If email provided, update linked user as well
                if (isset($data['email']) && $student->user) {
                    $student->user->email = $data['email'];
                    $student->user->save();
                }

                $student->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Student updated successfully',
                    'student' => $student->fresh()->load('user')
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update student: ' . $e->getMessage()
                ], 500);
            }
        }

        // Default (admin) full update via form request-style validation
        try {
            $validated = app(UpdateStudentRequest::class)->rules();
            $data = $request->validate($validated);
            
            // Ensure both grade_level and grade fields are updated for backward compatibility
            if (isset($data['grade_level'])) {
                $data['grade'] = 'Grade ' . $data['grade_level'];
            }
            
            $student->update($data);

            return redirect()->route('admin.students.index')
                            ->with('success', 'Student updated successfully');
                            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update student'])->withInput();
        }
    }

    public function destroy(Student $student)
    {
        try {
            DB::beginTransaction();
            
            // Delete associated user account if exists
            if ($student->user_id && $student->user) {
                $student->user->delete();
            }
            
            // Delete the student record
            $student->delete();

            DB::commit();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Student deleted successfully'
                ]);
            }

            return redirect()->route('admin.students.index')
                            ->with('success', 'Student deleted successfully');
                            
        } catch (\Exception $e) {
            DB::rollBack();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete student: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.students.index')
                            ->with('error', 'Failed to delete student');
        }
    }

    public function updateStatus(Request $request, Student $student)
    {
        $request->validate([
            'status' => 'required|in:Active,enrolled,inactive,graduated,pending,missing_docs,submitted'
        ]);

        $student->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated',
            'status' => $student->status,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_year' => 'required|string|max:20',
            'lrn_number' => 'required|string|max:40|unique:students',
            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'mi' => 'required|string|max:10',
            'sex' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'religion' => 'required|string|max:100',
            'grade' => 'required|string|max:20',
            'current_address' => 'required|string|max:200',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ]);

        try {
            DB::beginTransaction();

            // Create or link user account
            $generatedEmail = $request->lrn_number . '@student.school.com';
            $email = $request->input('email') ?: $generatedEmail;
            $name = trim($request->firstname.' '.($request->mi ? $request->mi.' ' : '').$request->lastname);

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'user_id' => 'S' . str_pad(rand(1, 9999999999), 10, '0', STR_PAD_LEFT),
                    'password' => Hash::make($request->password),
                    'role' => 'student',
                    'status' => 'active',
                ]
            );

            // Create student profile with unified APP application id
            // Ensure unique APP id across table
            do {
                $appId = 'APP' . date('Y') . '-' . str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            } while (Student::where('application_id', $appId)->exists());

            $studentData = array_merge(
                $request->all(),
                [
                    'user_id' => $user->id,
                    'application_id' => $appId,
                    'email' => $email,
                ]
            );
            
            // Ensure both grade_level and grade fields are set for backward compatibility
            if (isset($studentData['grade_level'])) {
                $studentData['grade'] = 'Grade ' . $studentData['grade_level'];
            }
            
            $student = Student::create($studentData);

            // Auto-verify in non-prod if not verified
            $requireVerification = (bool) env('REQUIRE_EMAIL_VERIFICATION', app()->environment('production'));
            if (!$requireVerification && is_null($user->email_verified_at)) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Student enrolled successfully',
                'student' => $student->load('user')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to enroll student: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkEnrollmentStatus(Request $request)
    {
        $request->validate(['lrn_number' => 'required|string']);

        $student = Student::where('lrn_number', $request->lrn_number)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ]);
        }

        return response()->json([
            'success' => true,
            'student' => [
                'name' => $student->full_name,
                'lrn_number' => $student->lrn_number,
                'grade' => $student->grade,
                'status' => $student->status,
                'school_year' => $student->school_year
            ]
        ]);
    }
}