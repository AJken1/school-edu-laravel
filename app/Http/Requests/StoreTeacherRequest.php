<?php
// app/Http/Requests/StoreTeacherRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isTeacher());
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:200',
            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'employee_id' => 'nullable|string|max:20|unique:teachers,employee_id',
            'email' => 'required|email|unique:users,email|unique:teachers,email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'gender' => 'required|in:Male,Female',
            'phone' => 'required|string|max:20',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'required|string',
            'age' => 'required|integer|min:18|max:80',
            'date_of_birth' => 'required|date',
            'position' => 'nullable|string|max:50',
            'department' => 'nullable|string|max:100',
            'status' => 'nullable|in:Active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}