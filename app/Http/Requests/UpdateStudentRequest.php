<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isTeacher());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'school_year' => 'required|string|max:20',
            'lrn_number' => 'required|string|max:40|unique:students,lrn_number,' . $this->student->id,
            'email' => 'nullable|email|unique:students,email,' . $this->student->id,
            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'mi' => 'required|string|max:10',
            'sex' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'religion' => 'required|string|max:100',
            'grade' => 'nullable|string|max:20',
            'grade_level' => 'required|integer|between:1,12',
            'current_address' => 'required|string|max:200',
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
            'medical_conditions' => 'nullable|string',
            'additional_notes' => 'nullable|string',
            'status' => 'in:Active,enrolled,inactive,graduated,pending,missing_docs,submitted',
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
