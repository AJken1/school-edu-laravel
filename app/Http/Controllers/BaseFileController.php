<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

abstract class BaseFileController extends Controller
{
    /**
     * Validate file upload with additional security checks
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return array|bool Array with error message if validation fails, true if passes
     */
    protected function validateFileUpload($file)
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
     * Download a file
     *
     * @param string $filePath
     * @param string $fileName
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    protected function downloadFile($filePath, $fileName)
    {
        if (!Storage::exists($filePath)) {
            abort(404, 'File not found.');
        }
        
        return Storage::download($filePath, $fileName);
    }
    
    /**
     * View a file inline
     *
     * @param string $filePath
     * @param string $fileName
     * @param string|null $mimeType
     * @return \Illuminate\Http\Response
     */
    protected function viewFile($filePath, $fileName, $mimeType = null)
    {
        if (!Storage::exists($filePath)) {
            abort(404, 'File not found.');
        }
        
        $fileContent = Storage::get($filePath);
        $mimeType = $mimeType ?: Storage::mimeType($filePath);
        
        return Response::make($fileContent, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"'
        ]);
    }
    
    /**
     * Delete a file
     *
     * @param string $filePath
     * @return bool
     */
    protected function deleteFile($filePath)
    {
        if (Storage::exists($filePath)) {
            return Storage::delete($filePath);
        }
        
        return true;
    }
    
    /**
     * Store a file with secure handling
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path
     * @return string The stored file path
     */
    protected function storeFile($file, $path)
    {
        return $file->store($path);
    }
}
