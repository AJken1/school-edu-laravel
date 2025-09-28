<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Admin;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getDashboardStats()
    {
        $stats = [
            'studentCount' => Student::count(),
            'teacherCount' => Teacher::count(),
            'subjectCount' => Subject::count(),
            'adminCount' => Admin::count(),
        ];

        return response()->json([
            'status' => 'success',
            ...$stats
        ]);
    }

    public function adminDashboard()
    {
        $stats = [
            'studentCount' => Student::count(),
            'teacherCount' => Teacher::count(),
            'subjectCount' => Subject::count(),
            'totalUsers' => \App\Models\User::count(),
        ];

        // Analytics data for charts
        $analytics = $this->getAnalyticsData();

        return view('admin.dashboard', compact('stats', 'analytics'));
    }

    private function getAnalyticsData()
    {
        // Get current school year (assuming it's based on current year)
        $currentYear = now()->year;
        
        // Monthly enrollment data for the current year (line chart)
        $monthlyEnrollments = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthEnrollments = Student::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->count();
            $monthlyEnrollments[] = $monthEnrollments;
        }

        // Grade level distribution (bar chart) - handle null values properly
        $gradeDistribution = Student::selectRaw('COALESCE(grade_level, grade) as grade, COUNT(*) as count')
            ->where(function($query) {
                $query->whereNotNull('grade_level')
                      ->orWhereNotNull('grade');
            })
            ->groupByRaw('COALESCE(grade_level, grade)')
            ->orderBy('grade')
            ->get();

        // Status distribution (doughnut chart data)
        $statusDistribution = Student::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Graduation data by year (last 5 years)
        $graduationData = [];
        for ($i = 4; $i >= 0; $i--) {
            $year = $currentYear - $i;
            $graduated = Student::where('status', 'graduated')
                ->whereYear('created_at', $year)
                ->count();
            $graduationData[] = [
                'year' => $year,
                'graduated' => $graduated
            ];
        }

        return [
            'monthlyEnrollments' => $monthlyEnrollments,
            'gradeDistribution' => $gradeDistribution,
            'statusDistribution' => $statusDistribution,
            'graduationData' => collect($graduationData),
            'currentYear' => $currentYear
        ];
    }

    public function teacherDashboard()
    {
        $user = auth()->user();
        $teacher = $user->teacher;

        // Auto-provision a minimal teacher profile for users with teacher role
        if (!$teacher && ($user->role ?? null) === 'teacher') {
            // The teachers table has several non-nullable columns; provide safe defaults
            $teacher = \App\Models\Teacher::create([
                'user_id' => $user->id,
                'name' => $user->name ?? 'Teacher ' . $user->id,
                'gender' => 'Male',
                'phone' => '',
                'address' => 'N/A',
                'age' => 0,
                'image' => 'user.png',
            ]);
        }
        
        // Get teacher's personal data
        $teacherData = [
            'teacher' => $teacher,
            'user' => $user,
            'department' => $teacher ? $teacher->department : null,
            'status' => $teacher ? ($teacher->status ?: 'Active') : 'Unknown',
            'employment_date' => $teacher ? $teacher->created_at : null,
        ];
        
        // Get all students (teachers can see all students)
        $students = Student::where('status', 'Active')
            ->with('user')
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->limit(10)
            ->get();
        
        // Get teacher's subjects
        $subjects = Subject::orderBy('subject_name')->get();
        
        // Get recent files uploaded by students
        $recentFiles = \App\Models\StudentFile::with('student.user')
            ->latest()
            ->limit(5)
            ->get();
        
        // Get stats
        $stats = [
            'studentCount' => Student::where('status', 'Active')->count(),
            'subjectCount' => Subject::count(),
            'pendingFiles' => \App\Models\StudentFile::where('status', 'pending')->count(),
            'totalFiles' => $teacher ? $teacher->files()->count() : 0, // Teacher's own files count
        ];

        return view('teacher.dashboard', compact('teacherData', 'students', 'subjects', 'recentFiles', 'stats'));
    }

    public function studentDashboard()
    {
        $user = auth()->user();
        $student = $user->student;
        
        // Handle case where student record doesn't exist
        if (!$student) {
            return view('student.dashboard', [
                'studentData' => [
                    'student' => null,
                    'user' => $user,
                    'grade' => null,
                    'status' => 'No Record',
                    'enrollment_date' => null,
                ],
                'classmates' => collect(),
                'documents' => collect(),
            ]);
        }
        
        // Get student's personal data
        $studentData = [
            'student' => $student,
            'user' => $user,
            'grade' => $student->grade_level ?: $student->grade,
            'status' => $student->status ?: 'Unknown',
            'enrollment_date' => $student->created_at,
        ];
        
        // Get classmates (students in the same grade level)
        $classmates = collect(); // Initialize as empty collection
        if ($student && ($student->grade_level || $student->grade)) {
            $grade = $student->grade_level ?: $student->grade;
            try {
                $classmates = Student::where(function($query) use ($grade) {
                    $query->where('grade_level', $grade)
                          ->orWhere('grade', $grade);
                })
                ->where('status', 'Active')
                ->where('id', '!=', $student->id)
                ->with('user')
                ->limit(10)
                ->get();
            } catch (\Exception $e) {
                // If there's any error, keep classmates as empty collection
                $classmates = collect();
            }
        }
        
        // Get student's files
        $documents = collect(); // Initialize as empty collection
        if ($student) {
            try {
                $documents = $student->files()->latest()->limit(5)->get();
            } catch (\Exception $e) {
                // If there's any error, keep documents as empty collection
                $documents = collect();
            }
        }
        
        // Debug: Ensure all variables are proper collections
        if (!is_object($classmates) || !method_exists($classmates, 'count')) {
            $classmates = collect();
        }
        if (!is_object($documents) || !method_exists($documents, 'count')) {
            $documents = collect();
        }
        
        return view('student.dashboard', compact('studentData', 'classmates', 'documents'));
    }

    public function studentGrades()
    {
        $user = auth()->user();
        $student = $user->student;
        
        if (!$student || (!$student->grade_level && !$student->grade)) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Grade level not assigned yet.');
        }
        
        $grade = $student->grade_level ?: $student->grade;
        
        // Get all students in the same grade level who are enrolled and approved
        $gradeStudents = Student::where(function($query) use ($grade) {
            $query->where('grade_level', $grade)
                  ->orWhere('grade', $grade);
        })
        ->whereIn('status', ['Active', 'enrolled'])
        ->with('user')
        ->orderBy('lastname')
        ->orderBy('firstname')
        ->get();
        
        return view('student.grades', compact('gradeStudents', 'grade'));
    }

    public function ownerDashboard()
    {
        $stats = [
            'studentCount' => Student::count(),
            'teacherCount' => Teacher::count(),
            'subjectCount' => Subject::count(),
            'totalUsers' => \App\Models\User::count(),
            'adminCount' => \App\Models\User::where('role', 'admin')->count(),
        ];

        return view('owner.dashboard', compact('stats'));
    }
}