<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentFile;
use App\Models\Subject;
use Illuminate\Http\Request;

class TeacherFilesController extends Controller
{
    public function index(Request $request)
    {
        // Get all students with their files
        $studentsQuery = Student::with(['user', 'files'])
            ->where('status', 'Active')
            ->orderBy('lastname')
            ->orderBy('firstname');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $studentsQuery->where(function($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('lrn_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('email', 'like', "%{$search}%");
                  });
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $studentsQuery->where('status', $request->status);
        }

        $students = $studentsQuery->paginate(15);

        // Calculate statistics
        $stats = [
            'totalStudents' => Student::where('status', 'Active')->count(),
            'activeStudents' => Student::where('status', 'Active')->count(),
            'graduated' => Student::where('status', 'graduated')->count(),
            'pendingFiles' => StudentFile::where('status', 'pending')->count(),
            'approvedFiles' => StudentFile::where('status', 'approved')->count(),
            'missingFiles' => 0, // Could be calculated based on required documents
        ];

        // Get all files for statistics
        $allFiles = StudentFile::with('student.user')->latest()->get();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'students' => $students->items(),
                'stats' => $stats,
                'files' => $allFiles,
                'pagination' => [
                    'total' => $students->total(),
                    'from' => $students->firstItem(),
                    'to' => $students->lastItem(),
                    'currentPage' => $students->currentPage(),
                    'lastPage' => $students->lastPage()
                ]
            ]);
        }

        return view('teacher.files.index', compact('students', 'stats', 'allFiles'));
    }

    public function show(Student $student)
    {
        $student->load(['user', 'files']);
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'student' => $student
            ]);
        }

        return view('teacher.files.show', compact('student'));
    }

    public function updateFileStatus(Request $request, StudentFile $file)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $file->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'File status updated successfully',
            'status' => $file->status
        ]);
    }

    public function updateStudentStatus(Request $request, Student $student)
    {
        $request->validate([
            'status' => 'required|in:Active,enrolled,pending,graduated,inactive'
        ]);

        $student->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Student status updated successfully',
            'status' => $student->status
        ]);
    }

    public function download(StudentFile $file)
    {
        if (!file_exists(storage_path('app/' . $file->file_path))) {
            abort(404, 'File not found');
        }

        return response()->download(storage_path('app/' . $file->file_path), $file->file_name);
    }

    public function view(StudentFile $file)
    {
        if (!file_exists(storage_path('app/' . $file->file_path))) {
            abort(404, 'File not found');
        }

        $filePath = storage_path('app/' . $file->file_path);
        $mimeType = mime_content_type($filePath);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $file->file_name . '"'
        ]);
    }

    public function destroy(StudentFile $file)
    {
        // Delete physical file
        if (file_exists(storage_path('app/' . $file->file_path))) {
            unlink(storage_path('app/' . $file->file_path));
        }

        $file->delete();

        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully'
        ]);
    }

    public function getStats()
    {
        $stats = [
            'totalStudents' => Student::count(),
            'activeStudents' => Student::where('status', 'Active')->count(),
            'graduated' => Student::where('status', 'graduated')->count(),
            'pendingFiles' => StudentFile::where('status', 'pending')->count(),
            'approvedFiles' => StudentFile::where('status', 'approved')->count(),
            'rejectedFiles' => StudentFile::where('status', 'rejected')->count(),
            'totalFiles' => StudentFile::count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}
