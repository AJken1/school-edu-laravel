@extends('layouts.admin')

@section('title', 'Edit Student - EDUgate Admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center py-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Edit Student</h1>
            <p class="text-muted mb-0">Update student enrollment information</p>
        </div>
        <div>
            <a href="{{ route('admin.students.show', $student) }}" class="btn btn-info me-2">
                <i class="fas fa-eye me-2"></i>View Profile
            </a>
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Students
            </a>
        </div>
    </div>

    <!-- Student Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Student Information</h6>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>There were problems with your submission:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form id="studentForm" action="{{ route('admin.students.update', $student) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="firstname" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="firstname" name="firstname" value="{{ old('firstname', $student->firstname) }}" required>
                            @error('firstname')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lastname" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="lastname" name="lastname" value="{{ old('lastname', $student->lastname) }}" required>
                            @error('lastname')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="grade_level" class="form-label">Grade Level <span class="text-danger">*</span></label>
                            <select class="form-control" id="grade_level" name="grade_level" required>
                                <option value="">Select Grade</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('grade_level', $student->grade_level ?: $student->grade) == $i ? 'selected' : '' }}>Grade {{ $i }}</option>
                                @endfor
                            </select>
                            @error('grade_level')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="Active" {{ old('status', $student->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="enrolled" {{ old('status', $student->status) == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                                <option value="submitted" {{ old('status', $student->status) == 'submitted' ? 'selected' : '' }}>Application Submitted</option>
                                <option value="inactive" {{ old('status', $student->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lrn_number" class="form-label">LRN Number</label>
                            <input type="text" class="form-control" id="lrn_number" name="lrn_number" value="{{ old('lrn_number', $student->lrn_number) }}" readonly>
                            <small class="text-muted">LRN cannot be changed after enrollment</small>
                            @error('lrn_number')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <hr class="my-4">
                <h5 class="text-gray-800 mb-3">Contact Information</h5>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $student->email) }}">
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="contact_number" name="contact_number" value="{{ old('contact_number', $student->contact_number) }}">
                            @error('contact_number')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="current_address" class="form-label">Current Address</label>
                    <textarea class="form-control" id="current_address" name="current_address" rows="3">{{ old('current_address', $student->current_address) }}</textarea>
                    @error('current_address')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Academic Information -->
                <hr class="my-4">
                <h5 class="text-gray-800 mb-3">Academic Information</h5>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="school_year" class="form-label">School Year</label>
                            <input type="text" class="form-control" id="school_year" name="school_year" value="{{ old('school_year', $student->school_year) }}" readonly>
                            <small class="text-muted">School year is set during enrollment</small>
                            @error('school_year')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="application_id" class="form-label">Application ID</label>
                            <input type="text" class="form-control" id="application_id" value="{{ $student->application_id ?: 'Not assigned' }}" readonly>
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <hr class="my-4">
                <h5 class="text-gray-800 mb-3">Personal Information</h5>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="mi" class="form-label">Middle Initial</label>
                            <input type="text" class="form-control" id="mi" name="mi" value="{{ old('mi', $student->mi) }}" maxlength="10">
                            @error('mi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sex" class="form-label">Sex</label>
                            <select class="form-control" id="sex" name="sex">
                                <option value="">Select Sex</option>
                                <option value="Male" {{ old('sex', $student->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('sex', $student->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('sex')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '') }}">
                            @error('date_of_birth')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="religion" class="form-label">Religion</label>
                            <input type="text" class="form-control" id="religion" name="religion" value="{{ old('religion', $student->religion) }}">
                            @error('religion')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Special Needs -->
                <hr class="my-4">
                <h5 class="text-gray-800 mb-3">Special Needs Information</h5>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-check mb-3">
                            <input type="hidden" name="pwd" value="0">
                            <input class="form-check-input" type="checkbox" id="pwd" name="pwd" value="1" {{ old('pwd', $student->pwd) ? 'checked' : '' }}>
                            <label class="form-check-label" for="pwd">
                                Student is a Person with Disability (PWD)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group" id="pwd_details_group" style="display: {{ old('pwd', $student->pwd) ? 'block' : 'none' }};">
                    <label for="pwd_details" class="form-label">PWD Details</label>
                    <textarea class="form-control" id="pwd_details" name="pwd_details" rows="2">{{ old('pwd_details', $student->pwd_details) }}</textarea>
                    @error('pwd_details')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Additional Information -->
                <hr class="my-4">
                <h5 class="text-gray-800 mb-3">Additional Information</h5>

                <div class="form-group">
                    <label for="medical_conditions" class="form-label">Medical Conditions</label>
                    <textarea class="form-control" id="medical_conditions" name="medical_conditions" rows="2" placeholder="Any medical conditions, allergies, or health concerns">{{ old('medical_conditions', $student->medical_conditions) }}</textarea>
                    @error('medical_conditions')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="additional_notes" class="form-label">Additional Notes</label>
                    <textarea class="form-control" id="additional_notes" name="additional_notes" rows="3" placeholder="Any additional information about the student">{{ old('additional_notes', $student->additional_notes) }}</textarea>
                    @error('additional_notes')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Form Actions -->
                <hr class="my-4">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pwdCheckbox = document.getElementById('pwd');
    const pwdDetailsGroup = document.getElementById('pwd_details_group');

    // PWD checkbox toggle
    pwdCheckbox.addEventListener('change', function() {
        if (this.checked) {
            pwdDetailsGroup.style.display = 'block';
        } else {
            pwdDetailsGroup.style.display = 'none';
            document.getElementById('pwd_details').value = '';
        }
    });
});
</script>
@endpush
