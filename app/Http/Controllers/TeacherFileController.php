<?php

namespace App\Http\Controllers;

use App\Models\TeacherFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TeacherFileController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to access this page.');
            }
            
            $teacher = $user->teacher;
            
            if (!$teacher) {
                return redirect()->route('teacher.dashboard')
                    ->with('error', 'Teacher profile not found. Please contact administrator to set up your teacher profile.');
            }

            // Get teacher's files grouped by category
            $files = $teacher->files()->active()->orderBy('created_at', 'desc')->get();
            
            $filesByCategory = $files->groupBy('category');
            
            // Statistics
            $stats = [
                'totalFiles' => $files->count(),
                'lesson_plan' => $files->where('category', 'lesson_plan')->count(),
                'resource' => $files->where('category', 'resource')->count(),
                'certificate' => $files->where('category', 'certificate')->count(),
                'photo' => $files->where('category', 'photo')->count(),
                'education' => $files->where('category', 'education')->count(),
                'other' => $files->where('category', 'other')->count(),
            ];

            return view('teacher.files.index', compact('files', 'filesByCategory', 'stats'));
            
        } catch (\Exception $e) {
            \Log::error('TeacherFileController@index error: ' . $e->getMessage());
            return redirect()->route('teacher.dashboard')
                ->with('error', 'An error occurred while loading files. Please try again.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required|array|max:1',
            'files.*' => 'file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png,gif,zip,rar',
            'category' => 'required|in:lesson_plan,resource,certificate,photo,education,other',
        ], [
            'files.required' => 'Please select a file to upload.',
            'files.max' => 'You can only upload one file per category.',
            'files.*.file' => 'The file must be a valid file.',
            'files.*.max' => 'The file must not exceed 10MB.',
            'files.*.mimes' => 'File type not supported. Allowed types: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, JPG, JPEG, PNG, GIF, ZIP, RAR.',
            'category.required' => 'Please select a category for your file.',
            'category.in' => 'Invalid category selected.',
        ]);

        $user = auth()->user();
        $teacher = $user->teacher;
        
        if (!$teacher) {
            return back()->with('error', 'Teacher profile not found.');
        }

        // Delete existing file in this category if it exists
        $existingFile = $teacher->files()->where('category', $request->category)->first();
        if ($existingFile) {
            // Delete the physical file
            if (Storage::disk('public')->exists($existingFile->file_path)) {
                Storage::disk('public')->delete($existingFile->file_path);
            }
            // Delete the database record
            $existingFile->delete();
        }

        $file = $request->file('files')[0];
        try {
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = 'teacher_files/' . $teacher->id . '/' . $fileName;
            
            // Store file
            Storage::disk('public')->put($filePath, file_get_contents($file));
            
            // Save to database
            $teacherFile = TeacherFile::create([
                'teacher_id' => $teacher->id,
                'file_name' => $fileName,
                'original_name' => $originalName,
                'file_path' => $filePath,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'category' => $request->category,
                'description' => null,
                'status' => 'active',
            ]);

            $categoryNames = [
                'lesson_plan' => 'Lesson Plan',
                'resource' => 'Teaching Resource',
                'certificate' => 'Teaching Certificate',
                'photo' => 'Profile Photo',
                'education' => 'Educational Document',
                'other' => 'Other Document',
            ];

            $categoryName = $categoryNames[$request->category] ?? $request->category;
            
            return back()->with('success', $categoryName . ' uploaded successfully!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to upload file: ' . $e->getMessage());
        }
    }

    public function download(TeacherFile $file)
    {
        // Check if the file belongs to the authenticated teacher
        if ($file->teacher_id !== auth()->user()->teacher->id) {
            abort(403, 'Unauthorized access to file.');
        }

        if (!Storage::disk('public')->exists($file->file_path)) {
            abort(404, 'File not found.');
        }

        return response()->download(Storage::disk('public')->path($file->file_path), $file->original_name);
    }

    public function view(TeacherFile $file)
    {
        // Check if the file belongs to the authenticated teacher
        if ($file->teacher_id !== auth()->user()->teacher->id) {
            abort(403, 'Unauthorized access to file.');
        }

        if (!Storage::disk('public')->exists($file->file_path)) {
            abort(404, 'File not found.');
        }

        $filePath = Storage::disk('public')->path($file->file_path);
        $mimeType = mime_content_type($filePath);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $file->original_name . '"'
        ]);
    }

    public function update(Request $request, TeacherFile $file)
    {
        // Check if the file belongs to the authenticated teacher
        if ($file->teacher_id !== auth()->user()->teacher->id) {
            abort(403, 'Unauthorized access to file.');
        }

        $request->validate([
            'category' => 'required|in:lesson_plan,resource,certificate,photo,education,other',
            'description' => 'nullable|string|max:500',
        ]);

        $file->update([
            'category' => $request->category,
            'description' => $request->description,
        ]);

        return back()->with('success', 'File updated successfully!');
    }

    public function destroy(TeacherFile $file)
    {
        // Check if the file belongs to the authenticated teacher
        if ($file->teacher_id !== auth()->user()->teacher->id) {
            abort(403, 'Unauthorized access to file.');
        }

        try {
            // Delete physical file
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }

            // Delete database record
            $file->delete();

            return back()->with('success', 'File deleted successfully!');
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete file: ' . $e->getMessage()]);
        }
    }

    public function getStats()
    {
        $user = auth()->user();
        $teacher = $user->teacher;
        
        if (!$teacher) {
            return response()->json(['error' => 'Teacher profile not found.'], 404);
        }

        $files = $teacher->files()->active();
        
        $stats = [
            'totalFiles' => $files->count(),
            'lessonPlans' => $files->where('category', 'lesson_plan')->count(),
            'resources' => $files->where('category', 'resource')->count(),
            'certificates' => $files->where('category', 'certificate')->count(),
            'photos' => $files->where('category', 'photo')->count(),
            'educationalDocs' => $files->where('category', 'education')->count(),
            'other' => $files->where('category', 'other')->count(),
            'totalSize' => $files->sum('file_size'),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}