<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentSelfUpdateRequest;
use Illuminate\Http\Request;

class StudentSelfController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('student.dashboard')
                ->with('error', 'No student record found for your account.');
        }

        return view('student.edit', compact('student', 'user'));
    }

    public function update(StudentSelfUpdateRequest $request)
    {
        $user = $request->user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('student.dashboard')
                ->with('error', 'No student record found for your account.');
        }

        $student->update($request->validated());

        return redirect()->route('student.dashboard')
            ->with('success', 'Your enrollment information has been updated.');
    }
}


