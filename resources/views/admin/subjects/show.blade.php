@extends('layouts.admin')

@section('title', 'Subject Details - EDUgate Admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center py-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Subject Details</h1>
            <p class="text-muted mb-0">View information for this subject</p>
        </div>
        <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Subjects
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="mb-3"><strong>Name:</strong> {{ $subject->subject_name }}</div>
            <div class="mb-3"><strong>Class:</strong> {{ $subject->class }}</div>
            <div class="mb-3"><strong>Teacher:</strong> {{ optional($subject->teacher)->firstname }} {{ optional($subject->teacher)->lastname }}</div>
            <div class="text-muted">Created: {{ $subject->created_at->format('M d, Y') }}</div>
        </div>
    </div>
</div>
@endsection
