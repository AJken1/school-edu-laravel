<?php
// app/Http/Controllers/TeacherController.php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Http\Requests\StoreTeacherRequest;
// Using inline validation for update to match AJAX form
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        $teachers = $query->orderBy('created_at', 'desc')->paginate(15);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'teachers' => $teachers->items(),
                'totalPages' => $teachers->lastPage(),
                'currentPage' => $teachers->currentPage()
            ]);
        }

        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function edit(Teacher $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function destroy(Teacher $teacher)
    {
        try {
            \Log::info('Attempting to delete teacher', ['teacher_id' => $teacher->id, 'teacher_name' => $teacher->firstname . ' ' . $teacher->lastname]);
            
            DB::beginTransaction();

            // Delete profile picture if exists
            if ($teacher->profile_picture && $teacher->profile_picture !== 'user.png') {
                Storage::delete('public/' . $teacher->profile_picture);
                \Log::info('Deleted teacher profile picture');
            }

            // Delete associated user account
            if ($teacher->user) {
                $teacher->user->delete();
                \Log::info('Deleted associated user account');
            }
            
            $teacher->delete();
            \Log::info('Teacher record deleted successfully');

            DB::commit();

            return redirect()->route('admin.teachers.index')
                            ->with('success', 'Teacher deleted successfully');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Teacher delete error: ' . $e->getMessage(), [
                'teacher_id' => $teacher->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.teachers.index')
                            ->with('error', 'Failed to delete teacher: ' . $e->getMessage());
        }
    }

    public function show(Teacher $teacher)
    {
        $teacher->load('user', 'subjects');
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'teacher' => $teacher
            ]);
        }

        return view('admin.teachers.show', compact('teacher'));
    }

    public function updateStatus(Request $request, Teacher $teacher)
    {
        $request->validate([
            'status' => 'required|in:Active,inactive'
        ]);

        $teacher->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated',
            'status' => $teacher->status,
        ]);
    }

    public function store(StoreTeacherRequest $request)
    {
        try {
            DB::beginTransaction();

            // Handle image upload
            $imageName = 'user.png';
            $profilePicture = null;
            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();
                $request->image->storeAs('public/teachers', $imageName);
                $profilePicture = 'teachers/' . $imageName;
            }

            // Create user account
            $user = User::create([
                'name' => $request->firstname . ' ' . $request->lastname,
                'user_id' => 'T' . str_pad(rand(1, 9999999999), 10, '0', STR_PAD_LEFT),
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'teacher'
            ]);

            // Create teacher profile
            $teacher = Teacher::create(array_merge(
                $request->validated(),
                [
                    'name' => $request->firstname . ' ' . $request->lastname,
                    'user_id' => $user->id,
                    'image' => $imageName,
                    'profile_picture' => $profilePicture,
                    'application_id' => 'T-' . date('Y') . '-' . str_pad((int) (microtime(true) * 1000) % 1000000, 6, '0', STR_PAD_LEFT)
                ]
            ));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Teacher added successfully',
                'teacher' => $teacher->load('user')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add teacher: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Teacher $teacher)
    {
        try {
            // Log the incoming request data for debugging
            \Log::info('Teacher update request data:', $request->all());
            
            // Validate the request data
            $validatedData = $request->validate([
                'firstname' => 'required|string|max:100',
                'lastname' => 'required|string|max:100',
                'employee_id' => 'nullable|string|max:20|unique:teachers,employee_id,' . $teacher->id,
                'email' => 'required|email|unique:teachers,email,' . $teacher->id,
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
            ]);

            \Log::info('Validation passed, validated data:', $validatedData);

            DB::beginTransaction();

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if it exists and is not the default
                if ($teacher->profile_picture && $teacher->profile_picture !== 'user.png') {
                    Storage::delete('public/' . $teacher->profile_picture);
                }

                $imageName = time() . '.' . $request->image->extension();
                $request->image->storeAs('public/teachers', $imageName);
                $validatedData['profile_picture'] = 'teachers/' . $imageName;
            }

            // Update the teacher record
            $teacher->update($validatedData);
            \Log::info('Teacher record updated successfully');

            // Update the associated user record if it exists
            if ($teacher->user) {
                $teacher->user->update([
                    'name' => $validatedData['firstname'] . ' ' . $validatedData['lastname'],
                    'email' => $validatedData['email'],
                ]);
                \Log::info('User record updated successfully');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Teacher updated successfully',
                'teacher' => $teacher->fresh()->load('user')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            \Log::error('Teacher update validation error:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Teacher update error: ' . $e->getMessage(), [
                'teacher_id' => $teacher->id,
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update teacher: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeProfilePicture(Teacher $teacher)
    {
        try {
            if ($teacher->profile_picture && $teacher->profile_picture !== 'user.png') {
                Storage::delete('public/' . $teacher->profile_picture);
                $teacher->update(['profile_picture' => null]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile picture removed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove profile picture'
            ], 500);
        }
    }
}