@extends('layouts.student')

@section('title', 'Edit Enrollment Info - EDUgate')

@section('content')
<div class="container-fluid px-4 attio-dashboard">
    <!-- Page Header -->
    <div class="page-header mb-5">
        <div>
            <h1 class="page-title">Edit My Enrollment Information</h1>
            <p class="page-subtitle">Update your personal and contact details.</p>
        </div>
        <a href="{{ route('student.dashboard') }}" class="action-btn-primary">
            <i class="fas fa-arrow-left"></i>
            <span>Back To Dashboard</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-secondary mb-4">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger mb-4">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('student.enrollment.update') }}">
        @csrf
        @method('PATCH')

        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="section-card">
                    <div class="section-header">
                        <h3 class="section-title">Personal Information</h3>
                    </div>
                    <div class="section-body">
                        <div class="mb-3">
                            <label class="attio-label">First Name</label>
                            <input type="text" name="firstname" class="attio-input" value="{{ old('firstname', $student->firstname ?? $student->first_name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="attio-label">Middle Initial</label>
                            <input type="text" name="mi" class="attio-input" value="{{ old('mi', $student->mi) }}">
                        </div>
                        <div class="mb-3">
                            <label class="attio-label">Last Name</label>
                            <input type="text" name="lastname" class="attio-input" value="{{ old('lastname', $student->lastname ?? $student->last_name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="attio-label">Sex</label>
                            <select name="sex" class="attio-input">
                                <option value="">Select</option>
                                <option value="Male" {{ old('sex', $student->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('sex', $student->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="attio-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="attio-input" value="{{ old('date_of_birth', optional($student->date_of_birth)->format('Y-m-d')) }}">
                        </div>
                        <div class="mb-3">
                            <label class="attio-label">Religion</label>
                            <input type="text" name="religion" class="attio-input" value="{{ old('religion', $student->religion) }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="section-card">
                    <div class="section-header">
                        <h3 class="section-title">Contact & Address</h3>
                    </div>
                    <div class="section-body">
                        <div class="mb-3">
                            <label class="attio-label">Email (for school contact)</label>
                            <input type="email" name="email" class="attio-input" value="{{ old('email', $student->email ?? $user->email) }}">
                        </div>
                        <div class="mb-3">
                            <label class="attio-label">Contact Number</label>
                            <input type="text" name="contact_number" class="attio-input" value="{{ old('contact_number', $student->contact_number) }}">
                        </div>
                        <div class="mb-3">
                            <label class="attio-label">Current Address</label>
                            <input type="text" name="current_address" class="attio-input" value="{{ old('current_address', $student->current_address ?? $student->address) }}">
                        </div>
                        <div class="mb-3">
                            <label class="attio-label">Medical Conditions</label>
                            <textarea name="medical_conditions" class="attio-input" rows="3">{{ old('medical_conditions', $student->medical_conditions) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="attio-label">Additional Notes</label>
                            <textarea name="additional_notes" class="attio-input" rows="3">{{ old('additional_notes', $student->additional_notes) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-card">
            <div class="section-body d-flex justify-content-end gap-2">
                <a href="{{ route('student.dashboard') }}" class="action-btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="action-btn-primary">
                    <i class="fas fa-save"></i>
                    <span>Save Changes</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

.attio-dashboard {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    color: #1a1a1a;
    background: #fafafa;
    min-height: 100vh;
    padding: 2rem 0;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2.5rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
    letter-spacing: -0.02em;
}

.page-subtitle {
    font-size: 0.9375rem;
    color: #666;
    margin: 0.5rem 0 0;
    font-weight: 400;
}

.action-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.125rem;
    background: #ffffff;
    border: 1px solid #e5e5e5;
    border-radius: 8px;
    color: #1a1a1a;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
    font-family: 'Inter', sans-serif;
}

.action-btn-primary:hover {
    background: #f5f5f5;
    border-color: #d0d0d0;
    color: #1a1a1a;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.action-btn-primary i {
    font-size: 0.875rem;
}

.action-btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: transparent;
    border: 1px solid #e5e5e5;
    border-radius: 6px;
    color: #1a1a1a;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    font-family: 'Inter', sans-serif;
}

.action-btn-secondary:hover {
    background: #f5f5f5;
    border-color: #d0d0d0;
}

.section-card {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    overflow: hidden;
}

.section-header {
    padding: 1.5rem;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.section-title {
    font-size: 1.125rem;
    font-weight: 500;
    color: #1a1a1a;
    margin: 0;
    letter-spacing: -0.01em;
}

.section-body {
    padding: 1.5rem;
}

.attio-label {
    font-size: 0.8125rem;
    color: #666;
    font-weight: 500;
    margin-bottom: 0.5rem;
    display: block;
}

.attio-input {
    border: 1px solid #e5e5e5;
    border-radius: 6px;
    padding: 0.625rem 0.875rem;
    font-size: 0.875rem;
    color: #1a1a1a;
    background: #ffffff;
    transition: all 0.2s ease;
    width: 100%;
    font-family: 'Inter', sans-serif;
}

.attio-input:focus {
    outline: none;
    border-color: #1a1a1a;
    box-shadow: 0 0 0 3px rgba(26, 26, 26, 0.05);
}

.attio-input textarea {
    resize: vertical;
}

.alert {
    padding: 1rem 1.25rem;
    border-radius: 8px;
    border: 1px solid #e5e5e5;
    background: #ffffff;
}

.alert-danger {
    border-color: #dc3545;
    background: #fff5f5;
    color: #dc3545;
}

.alert-secondary {
    border-color: #e5e5e5;
    background: #fafafa;
    color: #1a1a1a;
}

@media (max-width: 768px) {
    .attio-dashboard {
        padding: 1rem 0;
    }
    
    .page-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
}
</style>
@endpush