<?php
// app/Http/Requests/StoreStudentRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isTeacher());
    }

    public function rules(): array
    {
        return [
            'school_year' => 'required|string|max:20',
            'lrn_number' => 'required|string|max:40|unique:students',
            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'mi' => 'required|string|max:10',
            'sex' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'religion' => 'required|string|max:100',
            'grade' => 'required|string|max:20',
            'current_address' => 'required|string|max:200',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'pwd' => 'boolean',
            'pwd_details' => 'nullable|string|max:200',
            'father_firstname' => 'nullable|string|max:100',
            'father_lastname' => 'nullable|string|max:100',
            'father_mi' => 'nullable|string|max:10',
            'mother_firstname' => 'nullable|string|max:100',
            'mother_lastname' => 'nullable|string|max:100',
            'mother_mi' => 'nullable|string|max:10',
            'guardian_firstname' => 'nullable|string|max:100',
            'guardian_lastname' => 'nullable|string|max:100',
            'guardian_mi' => 'nullable|string|max:10',
            'contact_number' => 'required|string|max:20',
            'status' => 'in:Active,enrolled,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'lrn_number.unique' => 'This LRN number is already registered.',
            'sex.in' => 'Please select a valid gender.',
            'date_of_birth.required' => 'Date of birth is required.',
        ];
    }
}