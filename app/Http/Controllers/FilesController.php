<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class FilesController extends Controller
{
    /**
     * Display the files management page for admin.
     */
    public function index(Request $request)
    {
        // Improve eager loading to prevent N+1 query problems
        $query = Student::with(['files.reviewedBy', 'user']);
        
        // Filter by status if provided
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        // Search by student name or application ID - using parameter binding to prevent SQL injection
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('firstname', 'like', '%' . $search . '%')
                  ->orWhere('lastname', 'like', '%' . $search . '%')
                  ->orWhere('application_id', 'like', '%' . $search . '%')
                  ->orWhere('lrn_number', 'like', '%' . $search . '%');
            });
        }
        
        // Filter by file status
        if ($request->has('file_status') && $request->file_status !== '') {
            $query->whereHas('files', function($q) use ($request) {
                $q->where('status', $request->file_status);
            });
        }
        
        $students = $query->paginate(15)->appends($request->all());
        
        // Get statistics
        $stats = [
            'total_students' => Student::count(),
            'active_students' => Student::where('status', 'Active')->orWhere('status', 'enrolled')->count(),
            'graduated_students' => Student::where('status', 'graduated')->count(),
            'pending_files' => StudentFile::where('status', 'pending')->count(),
            'approved_files' => StudentFile::where('status', 'approved')->count(),
            'missing_files' => StudentFile::where('status', 'missing')->count(),
        ];
        
        return view('admin.files.index', compact('students', 'stats'));
    }
    
    /**
     * Show detailed files for a specific student.
     */
    public function show(Student $student)
    {
        $student->load(['files.reviewedBy', 'user']);
        
        // Get all required file types and check which ones are missing
        $requiredFiles = StudentFile::REQUIRED_FILE_TYPES;
        $existingFiles = $student->files->keyBy('file_type');
        
        $fileStatus = [];
        foreach ($requiredFiles as $type => $name) {
            $fileStatus[$type] = [
                'name' => $name,
                'file' => $existingFiles->get($type),
                'required' => true
            ];
        }
        
        return view('admin.files.show', compact('student', 'fileStatus'));
    }
    
    /**
     * Update file status (approve, reject, mark as missing).
     */
    public function updateFileStatus(Request $request, StudentFile $file)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,missing,pending',
            'notes' => 'nullable|string|max:1000'
        ]);
        
        $file->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'File status updated successfully.',
            'file' => $file->load('reviewedBy')
        ]);
    }
    
    /**
     * Download a student file.
     */
    public function download(StudentFile $file)
    {
        if (!Storage::exists($file->file_path)) {
            abort(404, 'File not found.');
        }
        
        return Storage::download($file->file_path, $file->file_name);
    }
    
    /**
     * View a student file (for images/PDFs).
     */
    public function view(StudentFile $file)
    {
        if (!Storage::exists($file->file_path)) {
            abort(404, 'File not found.');
        }
        
        $fileContent = Storage::get($file->file_path);
        $mimeType = $file->mime_type ?: Storage::mimeType($file->file_path);
        
        return Response::make($fileContent, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $file->file_name . '"'
        ]);
    }
    
    /**
     * Delete a student file.
     */
    public function destroy(StudentFile $file)
    {
        // Delete file from storage
        if (Storage::exists($file->file_path)) {
            Storage::delete($file->file_path);
        }
        
        $file->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully.'
        ]);
    }
    
    /**
     * Bulk update student status (active/graduated).
     */
    public function updateStudentStatus(Request $request, Student $student)
    {
        $request->validate([
            'status' => 'required|in:Active,enrolled,graduated,inactive,pending,missing_docs,submitted'
        ]);
        
        $student->update(['status' => $request->status]);
        
        return response()->json([
            'success' => true,
            'message' => 'Student status updated successfully.',
            'student' => $student
        ]);
    }
    
    /**
     * Get file statistics for dashboard.
     */
    public function getStats()
    {
        $stats = [
            'total_files' => StudentFile::count(),
            'pending_files' => StudentFile::where('status', 'pending')->count(),
            'approved_files' => StudentFile::where('status', 'approved')->count(),
            'rejected_files' => StudentFile::where('status', 'rejected')->count(),
            'missing_files' => StudentFile::where('status', 'missing')->count(),
            'students_with_complete_files' => Student::whereHas('files', function($q) {
                $q->where('status', 'approved');
            }, '=', count(StudentFile::REQUIRED_FILE_TYPES))->count(),
        ];
        
        return response()->json($stats);
    }
}
