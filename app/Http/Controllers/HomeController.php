<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\StudentFile;
use App\Http\Requests\EnrollmentRequest;
use App\Services\EnrollmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    protected $enrollmentService;
    
    public function __construct(EnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }
    
    public function index()
    {
        return view('home');
    }

    public function enrollment()
    {
        return view('enrollment');
    }

    public function submitEnrollment(EnrollmentRequest $request)
    {
        try {
            // Use the enrollment service to process the enrollment
            $student = $this->enrollmentService->processEnrollment($request->validated());

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