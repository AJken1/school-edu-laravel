@extends('layouts.admin')

@section('title', 'Add New Student - EDUgate Admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center py-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Add New Student</h1>
            <p class="text-muted mb-0">Enroll a new student to the system</p>
        </div>
        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Students
        </a>
    </div>

    <!-- Student Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Student Enrollment Form</h6>
        </div>
        <div class="card-body">
            <form id="studentForm">
                @csrf
                
                <!-- School Information -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="school_year" class="form-label">School Year <span class="text-danger">*</span></label>
                            <select class="form-control" id="school_year" name="school_year" required>
                                <option value="">Select School Year</option>
                                <option value="2024-2025" selected>2024-2025</option>
                                <option value="2025-2026">2025-2026</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lrn_number" class="form-label">LRN Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="lrn_number" name="lrn_number" placeholder="12-digit LRN" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <!-- Student Information -->
                <hr class="my-4">
                <h5 class="text-gray-800 mb-3">Student Information</h5>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="firstname" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="firstname" name="firstname" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="mi" class="form-label">Middle Initial <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="mi" name="mi" maxlength="10" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="lastname" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="lastname" name="lastname" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sex" class="form-label">Sex <span class="text-danger">*</span></label>
                            <select class="form-control" id="sex" name="sex" required>
                                <option value="">Select Sex</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="religion" class="form-label">Religion <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="religion" name="religion" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <hr class="my-4">
                <h5 class="text-gray-800 mb-3">Academic Information</h5>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="grade_level" class="form-label">Grade Level <span class="text-danger">*</span></label>
                            <select class="form-control" id="grade_level" name="grade_level" required>
                                <option value="">Select Grade</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">Grade {{ $i }}</option>
                                @endfor
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="contact_number" name="contact_number" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="current_address" class="form-label">Current Address <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="current_address" name="current_address" rows="3" required></textarea>
                    <div class="invalid-feedback"></div>
                </div>

                <!-- Account Information -->
                <hr class="my-4">
                <h5 class="text-gray-800 mb-3">Account Information</h5>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Optional - will auto-generate if empty">
                            <small class="form-text text-muted">If left empty, will use LRN@student.school.com</small>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" required minlength="8">
                            <small class="form-text text-muted">Minimum 8 characters</small>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <!-- PWD Information -->
                <hr class="my-4">
                <h5 class="text-gray-800 mb-3">Special Needs Information</h5>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="pwd" name="pwd" value="1">
                            <label class="form-check-label" for="pwd">
                                Student is a Person with Disability (PWD)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group" id="pwd_details_group" style="display: none;">
                    <label for="pwd_details" class="form-label">PWD Details</label>
                    <textarea class="form-control" id="pwd_details" name="pwd_details" rows="2" placeholder="Please specify the disability details"></textarea>
                    <div class="invalid-feedback"></div>
                </div>

                <!-- Parent/Guardian Information -->
                <hr class="my-4">
                <h5 class="text-gray-800 mb-3">Parent/Guardian Information</h5>

                <!-- Father Information -->
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-muted mb-3">Father's Information</h6>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="father_firstname" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="father_firstname" name="father_firstname">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="father_mi" class="form-label">Middle Initial</label>
                            <input type="text" class="form-control" id="father_mi" name="father_mi" maxlength="10">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="father_lastname" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="father_lastname" name="father_lastname">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <!-- Mother Information -->
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-muted mb-3">Mother's Information</h6>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="mother_firstname" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="mother_firstname" name="mother_firstname">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="mother_mi" class="form-label">Middle Initial</label>
                            <input type="text" class="form-control" id="mother_mi" name="mother_mi" maxlength="10">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="mother_lastname" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="mother_lastname" name="mother_lastname">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <!-- Guardian Information -->
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-muted mb-3">Guardian's Information (if different from parents)</h6>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="guardian_firstname" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="guardian_firstname" name="guardian_firstname">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="guardian_mi" class="form-label">Middle Initial</label>
                            <input type="text" class="form-control" id="guardian_mi" name="guardian_mi" maxlength="10">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="guardian_lastname" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="guardian_lastname" name="guardian_lastname">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <hr class="my-4">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save me-2"></i>Enroll Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle me-2"></i>Success
                </h5>
            </div>
            <div class="modal-body">
                <p class="mb-0">Student has been enrolled successfully!</p>
                <small class="text-muted">An account has been created automatically with the LRN as username.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="redirectToIndex()">Go to Students List</button>
                <button type="button" class="btn btn-primary" onclick="resetForm()">Enroll Another Student</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('studentForm');
    const submitBtn = document.getElementById('submitBtn');
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

    // Password confirmation validation
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('password_confirmation');
    
    function validatePasswordConfirmation() {
        if (passwordField.value !== confirmPasswordField.value) {
            confirmPasswordField.setCustomValidity('Passwords do not match');
            confirmPasswordField.classList.add('is-invalid');
            return false;
        } else {
            confirmPasswordField.setCustomValidity('');
            confirmPasswordField.classList.remove('is-invalid');
            return true;
        }
    }
    
    passwordField.addEventListener('input', validatePasswordConfirmation);
    confirmPasswordField.addEventListener('input', validatePasswordConfirmation);

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate password confirmation before submitting
        if (!validatePasswordConfirmation()) {
            alert('Please make sure passwords match.');
            return;
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enrolling Student...';
        
        const formData = new FormData(form);
        
        fetch('{{ route("admin.students.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(async response => {
            const contentType = response.headers.get('content-type') || '';
            if (contentType.includes('application/json')) {
                return response.json();
            }
            const text = await response.text();
            throw new Error(text);
        })
        .then(data => {
            if (data.success) {
                $('#successModal').modal('show');
                clearErrors();
            } else {
                if (data.errors) {
                    displayErrors(data.errors);
                } else {
                    alert('Error: ' + (data.message || 'Something went wrong'));
                }
            }
        })
        .catch(error => {
            console.error('Enroll error:', error);
            alert('An error occurred while enrolling the student');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Enroll Student';
        });
    });

    function displayErrors(errors) {
        clearErrors();
        
        for (const [field, messages] of Object.entries(errors)) {
            const input = document.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('is-invalid');
                const feedback = input.parentNode.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.textContent = messages[0];
                }
            }
        }
    }

    function clearErrors() {
        document.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
        document.querySelectorAll('.invalid-feedback').forEach(el => {
            el.textContent = '';
        });
    }
});

function resetForm() {
    document.getElementById('studentForm').reset();
    document.getElementById('pwd_details_group').style.display = 'none';
    
    // Clear password validation states
    document.getElementById('password').classList.remove('is-invalid');
    document.getElementById('password_confirmation').classList.remove('is-invalid');
    document.getElementById('password_confirmation').setCustomValidity('');
    
    $('#successModal').modal('hide');
}

function redirectToIndex() {
    window.location.href = '{{ route("admin.students.index") }}';
}
</script>
@endpush
