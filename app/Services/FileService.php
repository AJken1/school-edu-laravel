<?php

namespace App\Services;

use App\Models\StudentFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{
    /**
     * Validate file content
     *
     * @param UploadedFile $file
     * @return array|bool Array with error message if validation fails, true if passes
     */
    public function validateFileContent(UploadedFile $file)
    {
        // Check MIME type using finfo
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file->getPathname());
        $allowedMimeTypes = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/jpg',
        ];
        
        if (!in_array($mimeType, $allowedMimeTypes)) {
            return ['error' => 'Invalid file type detected. Please upload only PDF or image files.'];
        }
        
        return true;
    }
    
    /**
     * Store a student file
     *
     * @param UploadedFile $file
     * @param int $studentId
     * @param string $fileType
     * @return StudentFile
     */
    public function storeStudentFile(UploadedFile $file, int $studentId, string $fileType)
    {
        // Store the file
        $path = $file->store("student_files/{$studentId}");
        
        // Create the file record
        return StudentFile::create([
            'student_id' => $studentId,
            'file_type' => $fileType,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => (string) $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'status' => 'pending',
            'uploaded_at' => now(),
        ]);
    }
    
    /**
     * Update a student file
     *
     * @param StudentFile $studentFile
     * @param UploadedFile $file
     * @return StudentFile
     */
    public function updateStudentFile(StudentFile $studentFile, UploadedFile $file)
    {
        // Delete old file if exists
        if (Storage::exists($studentFile->file_path)) {
            Storage::delete($studentFile->file_path);
        }
        
        // Store new file
        $path = $file->store("student_files/{$studentFile->student_id}");
        
        // Update file record
        $studentFile->update([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => (string) $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'status' => 'pending', // re-review after replace
            'uploaded_at' => now(),
            'reviewed_at' => null,
            'reviewed_by' => null,
        ]);
        
        return $studentFile;
    }
    
    /**
     * Delete a student file
     *
     * @param StudentFile $studentFile
     * @return bool
     */
    public function deleteStudentFile(StudentFile $studentFile)
    {
        // Delete file from storage
        if (Storage::exists($studentFile->file_path)) {
            Storage::delete($studentFile->file_path);
        }
        
        // Delete file record
        return $studentFile->delete();
    }
    
    /**
     * Get file content for viewing
     *
     * @param string $filePath
     * @return array|null Array with content and mime type, or null if file not found
     */
    public function getFileContent(string $filePath)
    {
        if (!Storage::exists($filePath)) {
            return null;
        }
        
        return [
            'content' => Storage::get($filePath),
            'mime_type' => Storage::mimeType($filePath),
        ];
    }
}
