<?php

namespace App\Http\Controllers;

use App\Models\StudentFile;
use App\Http\Requests\StudentFileRequest;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentFilesController extends BaseFileController
{
    protected $fileService;
    
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }
    
    public function index(Request $request)
    {
        $user = $request->user();
        $student = $user->student;
        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'No student record found.');
        }

        $student->load('files');

        $requiredFiles = StudentFile::REQUIRED_FILE_TYPES;
        $existingFiles = $student->files->keyBy('file_type');

        return view('student.files', compact('student', 'requiredFiles', 'existingFiles'));
    }

    public function store(StudentFileRequest $request)
    {
        $user = $request->user();
        $student = $user->student;
        if (!$student) {
            return back()->with('error', 'No student record found.');
        }

        $validated = $request->validated();
        $uploaded = $validated['file'];
        
        // Use FileService for file validation
        $validation = $this->fileService->validateFileContent($uploaded);
        if ($validation !== true) {
            return back()->with('error', $validation['error']);
        }
        
        // If replacing existing, delete old
        $existing = $student->files()->where('file_type', $validated['file_type'])->first();
        if ($existing) {
            $this->fileService->deleteStudentFile($existing);
        }

        // Use FileService to store the file
        $file = $this->fileService->storeStudentFile(
            $uploaded, 
            $student->id, 
            $validated['file_type']
        );

        return back()->with('success', 'File uploaded successfully. It is now pending review.');
    }

    public function update(StudentFileRequest $request, StudentFile $file)
    {
        $user = $request->user();
        if ($file->student_id !== ($user->student->id ?? null)) {
            abort(403);
        }

        $validated = $request->validated();
        $uploaded = $validated['file'];
        
        // Use FileService for file validation
        $validation = $this->fileService->validateFileContent($uploaded);
        if ($validation !== true) {
            return back()->with('error', $validation['error']);
        }
        
        // Use FileService to update the file
        $this->fileService->updateStudentFile($file, $uploaded);

        return back()->with('success', 'File replaced successfully and is pending review.');
    }

    public function destroy(Request $request, StudentFile $file)
    {
        $user = $request->user();
        if ($file->student_id !== ($user->student->id ?? null)) {
            abort(403);
        }
        
        // Use FileService for file deletion
        $this->fileService->deleteStudentFile($file);
        
        return back()->with('success', 'File deleted successfully.');
    }

    public function view(Request $request, StudentFile $file)
    {
        $user = $request->user();
        if ($file->student_id !== ($user->student->id ?? null)) {
            abort(403);
        }
        
        // Get file content using FileService
        $fileData = $this->fileService->getFileContent($file->file_path);
        
        if (!$fileData) {
            abort(404, 'File not found.');
        }
        
        return response($fileData['content'], 200, [
            'Content-Type' => $fileData['mime_type'],
            'Content-Disposition' => 'inline; filename="' . $file->file_name . '"'
        ]);
    }
}


