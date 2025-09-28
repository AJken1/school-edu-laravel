@extends('layouts.student')

@section('title', 'Edit Enrollment Info - EDUgate')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-edit me-2"></i>Edit My Enrollment Information
            </h1>
            <p class="text-muted mb-0">Update your personal and contact details.</p>
        </div>
        <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('student.enrollment.update') }}">
        @csrf
        @method('PATCH')

        <div class="row">
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-id-card me-2"></i>Personal Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" name="firstname" class="form-control" value="{{ old('firstname', $student->firstname ?? $student->first_name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Middle Initial</label>
                            <input type="text" name="mi" class="form-control" value="{{ old('mi', $student->mi) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="lastname" class="form-control" value="{{ old('lastname', $student->lastname ?? $student->last_name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sex</label>
                            <select name="sex" class="form-select">
                                <option value="">Select</option>
                                <option value="Male" {{ old('sex', $student->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('sex', $student->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', optional($student->date_of_birth)->format('Y-m-d')) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Religion</label>
                            <input type="text" name="religion" class="form-control" value="{{ old('religion', $student->religion) }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">
                            <i class="fas fa-home me-2"></i>Contact & Address
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Email (for school contact)</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $student->email ?? $user->email) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $student->contact_number) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Current Address</label>
                            <input type="text" name="current_address" class="form-control" value="{{ old('current_address', $student->current_address ?? $student->address) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Medical Conditions</label>
                            <textarea name="medical_conditions" class="form-control" rows="3">{{ old('medical_conditions', $student->medical_conditions) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Additional Notes</label>
                            <textarea name="additional_notes" class="form-control" rows="3">{{ old('additional_notes', $student->additional_notes) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body d-flex justify-content-end gap-2">
                <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Save Changes
                </button>
            </div>
        </div>
    </form>
</div>
@endsection


