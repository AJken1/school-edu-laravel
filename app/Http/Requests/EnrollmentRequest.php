<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentRequest extends FormRequest
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
        return [
            // Identity
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'mi' => 'nullable|string|max:10',
            'email' => 'required|email|max:255',
            'contact_number' => 'required|string|max:20',
            // Academics
            'school_year' => 'nullable|string|max:20',
            'lrn_number' => 'nullable|string|max:40|unique:students,lrn_number',
            'grade' => 'required_without:grade_level|string|max:20',
            'grade_level' => 'required_without:grade|integer|between:1,12',
            // Demographics
            'sex' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'religion' => 'required|string|max:100',
            'current_address' => 'required|string|max:500',
            // Parents/Guardian
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'parent_email' => 'nullable|email',
            'relationship' => 'required|in:father,mother,guardian,other',
            'father_firstname' => 'nullable|string|max:100',
            'father_lastname' => 'nullable|string|max:100',
            'father_mi' => 'nullable|string|max:10',
            'mother_firstname' => 'nullable|string|max:100',
            'mother_lastname' => 'nullable|string|max:100',
            'mother_mi' => 'nullable|string|max:10',
            'guardian_firstname' => 'nullable|string|max:100',
            'guardian_lastname' => 'nullable|string|max:100',
            'guardian_mi' => 'nullable|string|max:10',
            // Special
            'pwd' => 'nullable|boolean',
            'pwd_details' => 'nullable|string|max:200',
            // Academics extra
            'previous_school' => 'nullable|string|max:255',
            // Consent
            'terms' => 'sometimes|accepted',
            // Optional password setup (if provided now)
            'password' => 'nullable|string|min:8|confirmed',
            // Required documents (optional at submission, but tracked)
            'birth_certificate' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'report_card' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'firstname.required' => 'First name is required.',
            'lastname.required' => 'Last name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'contact_number.required' => 'Contact number is required.',
            'grade.required_without' => 'Grade level is required.',
            'grade_level.required_without' => 'Grade level is required.',
            'sex.required' => 'Sex is required.',
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.date' => 'Please enter a valid date of birth.',
            'religion.required' => 'Religion is required.',
            'current_address.required' => 'Current address is required.',
            'parent_name.required' => 'Parent/guardian name is required.',
            'parent_phone.required' => 'Parent/guardian contact number is required.',
            'relationship.required' => 'Relationship to student is required.',
            'terms.accepted' => 'You must accept the terms and conditions.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }
}
