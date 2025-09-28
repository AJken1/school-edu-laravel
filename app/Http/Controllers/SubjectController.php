<?php
// app/Http/Controllers/SubjectController.php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Teacher;
// Removed specific FormRequests; using inline validation within controller
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject_name', 'like', "%{$search}%")
                  ->orWhere('class', 'like', "%{$search}%");
            });
        }

        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        $subjects = $query->orderBy('created_at', 'desc')->paginate(15);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'subjects' => $subjects->items(),
                'totalPages' => $subjects->lastPage(),
                'currentPage' => $subjects->currentPage()
            ]);
        }

        // Serve teacher-specific UI when accessed under teacher routes
        if ($request->routeIs('teacher.*')) {
            return view('teacher.subjects.index');
        }

        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('admin.subjects.create');
    }

    public function show(Subject $subject)
    {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'subject' => $subject
            ]);
        }
        return view('admin.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'subject_name' => 'required|string|max:100',
                'class' => 'required|string|max:50',
                'teacher_id' => 'nullable|exists:teachers,id',
            ]);

            $subject = Subject::create(array_merge(
                $data,
                ['subject_id' => ($data['class'] ?? 'CLS') . uniqid()]
            ));

            // Return JSON for AJAX requests, redirect for regular form submissions
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Subject created successfully',
                    'subject' => $subject->load('teacher')
                ]);
            }

            return redirect()->route('admin.subjects.index')
                ->with('success', 'Subject created successfully.');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create subject: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Failed to create subject: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function update(Request $request, Subject $subject)
    {
        try {
            $data = $request->validate([
                'subject_name' => 'required|string|max:100',
                'class' => 'required|string|max:50',
                'teacher_id' => 'nullable|exists:teachers,id',
            ]);

            $subject->update($data);

            // Return JSON for AJAX requests, redirect for regular form submissions
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Subject updated successfully',
                    'subject' => $subject->fresh()->load('teacher')
                ]);
            }

            return redirect()->route('admin.subjects.index')
                ->with('success', 'Subject updated successfully.');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update subject: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Failed to update subject: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(Request $request, Subject $subject)
    {
        try {
            $subject->delete();
            
            // Return JSON for AJAX requests, redirect for regular form submissions
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Subject deleted successfully'
                ]);
            }

            return redirect()->route('admin.subjects.index')
                ->with('success', 'Subject deleted successfully.');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete subject: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Failed to delete subject: ' . $e->getMessage()]);
        }
    }
}