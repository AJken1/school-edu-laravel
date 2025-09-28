@extends('layouts.admin')

@section('title', 'Edit Subject - EDUgate Admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center py-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Edit Subject</h1>
            <p class="text-muted mb-0">Update subject details</p>
        </div>
        <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Subjects
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Subject Details</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.subjects.update', $subject) }}">
                @csrf
                @method('PATCH')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="subject_name" class="form-label">Subject Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="subject_name" name="subject_name" value="{{ old('subject_name', $subject->subject_name) }}" required>
                            @error('subject_name')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="class" class="form-label">Class <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="class" name="class" value="{{ old('class', $subject->class) }}" required>
                            @error('class')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="teacher_id" class="form-label">Assign Teacher</label>
                            <select class="form-control" id="teacher_id" name="teacher_id">
                                <option value="">-- Optional --</option>
                                @foreach(\App\Models\Teacher::orderBy('firstname')->get() as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $subject->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->firstname }} {{ $teacher->lastname }}
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
