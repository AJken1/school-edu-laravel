<?php

namespace App\Http\Requests;

use App\Models\StudentFile;
use Illuminate\Foundation\Http\FormRequest;

class StudentFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];

        // Only require file_type for store requests, not for updates
        if ($this->isMethod('POST')) {
            $rules['file_type'] = 'required|string|in:' . implode(',', array_keys(StudentFile::REQUIRED_FILE_TYPES));
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'file.required' => 'Please select a file to upload.',
            'file.file' => 'The uploaded file is invalid.',
            'file.mimes' => 'Only PDF and image files (JPG, JPEG, PNG) are allowed.',
            'file.max' => 'The file size must not exceed 5MB.',
            'file_type.required' => 'Please select a file type.',
            'file_type.in' => 'The selected file type is invalid.',
        ];
    }
}
