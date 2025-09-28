<?php

namespace App\Http\Controllers;

use App\Models\StudentFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class StudentFilesController extends Controller
{
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

    public function store(Request $request)
    {
        $user = $request->user();
        $student = $user->student;
        if (!$student) {
            return back()->with('error', 'No student record found.');
        }

        $validated = $request->validate([
            'file_type' => 'required|string|in:' . implode(',', array_keys(StudentFile::REQUIRED_FILE_TYPES)),
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $uploaded = $validated['file'];
        $path = $uploaded->store("student_files/{$student->id}");

        // If replacing existing, delete old
        $existing = $student->files()->where('file_type', $validated['file_type'])->first();
        if ($existing) {
            if (Storage::exists($existing->file_path)) {
                Storage::delete($existing->file_path);
            }
            $existing->delete();
        }

        $file = $student->files()->create([
            'file_type' => $validated['file_type'],
            'file_name' => $uploaded->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => (string) $uploaded->getSize(),
            'mime_type' => $uploaded->getMimeType(),
            'status' => 'pending',
            'uploaded_at' => now(),
        ]);

        return back()->with('success', 'File uploaded successfully. It is now pending review.');
    }

    public function update(Request $request, StudentFile $file)
    {
        $user = $request->user();
        if ($file->student_id !== ($user->student->id ?? null)) {
            abort(403);
        }

        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Replace file
        if (Storage::exists($file->file_path)) {
            Storage::delete($file->file_path);
        }
        $uploaded = $validated['file'];
        $path = $uploaded->store("student_files/{$file->student_id}");

        $file->update([
            'file_name' => $uploaded->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => (string) $uploaded->getSize(),
            'mime_type' => $uploaded->getMimeType(),
            'status' => 'pending', // re-review after replace
            'uploaded_at' => now(),
            'reviewed_at' => null,
            'reviewed_by' => null,
        ]);

        return back()->with('success', 'File replaced successfully and is pending review.');
    }

    public function destroy(Request $request, StudentFile $file)
    {
        $user = $request->user();
        if ($file->student_id !== ($user->student->id ?? null)) {
            abort(403);
        }
        if (Storage::exists($file->file_path)) {
            Storage::delete($file->file_path);
        }
        $file->delete();
        return back()->with('success', 'File deleted successfully.');
    }

    public function view(Request $request, StudentFile $file)
    {
        $user = $request->user();
        if ($file->student_id !== ($user->student->id ?? null)) {
            abort(403);
        }
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
}


