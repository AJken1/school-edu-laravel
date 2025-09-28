<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentSelfUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'student';
    }

    public function rules(): array
    {
        $student = $this->user()->student;

        return [
            // Allow updating only personal/contact fields, not system/grade/status
            'firstname' => 'sometimes|required|string|max:100',
            'lastname' => 'sometimes|required|string|max:100',
            'mi' => 'sometimes|nullable|string|max:10',
            'first_name' => 'sometimes|nullable|string|max:100',
            'last_name' => 'sometimes|nullable|string|max:100',
            'sex' => 'sometimes|in:Male,Female',
            'gender' => 'sometimes|nullable|string|max:50',
            'date_of_birth' => 'sometimes|date',
            'religion' => 'sometimes|nullable|string|max:100',
            'current_address' => 'sometimes|nullable|string|max:200',
            'address' => 'sometimes|nullable|string|max:200',
            'contact_number' => 'sometimes|nullable|string|max:20',
            'email' => 'sometimes|nullable|email|unique:students,email,' . ($student?->id ?? 'NULL'),
            'pwd' => 'sometimes|boolean',
            'pwd_details' => 'sometimes|nullable|string|max:200',
            'father_firstname' => 'sometimes|nullable|string|max:100',
            'father_lastname' => 'sometimes|nullable|string|max:100',
            'father_mi' => 'sometimes|nullable|string|max:10',
            'mother_firstname' => 'sometimes|nullable|string|max:100',
            'mother_lastname' => 'sometimes|nullable|string|max:100',
            'mother_mi' => 'sometimes|nullable|string|max:10',
            'guardian_firstname' => 'sometimes|nullable|string|max:100',
            'guardian_lastname' => 'sometimes|nullable|string|max:100',
            'guardian_mi' => 'sometimes|nullable|string|max:10',
            'medical_conditions' => 'sometimes|nullable|string',
            'additional_notes' => 'sometimes|nullable|string',

            // Explicitly forbid fields students shouldn't change via validation whitelist
        ];
    }
}


