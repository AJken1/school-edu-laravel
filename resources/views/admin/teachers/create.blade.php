@extends('layouts.admin')

@section('title', 'Add New Teacher - EDUgate Admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center py-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Add New Teacher</h1>
            <p class="text-muted mb-0">Create a new teacher profile</p>
        </div>
        <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Teachers
        </a>
    </div>

    <!-- Teacher Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Teacher Information</h6>
                </div>
                <div class="card-body">
                    <form id="teacherForm" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Personal Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firstname" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lastname" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="employee_id" class="form-label">Employee ID</label>
                                    <input type="text" class="form-control" id="employee_id" name="employee_id" placeholder="Optional">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Information -->
                        <hr class="my-4">
                        <h5 class="text-gray-800 mb-3">Account Information</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password" required minlength="8">
                                    <small class="form-text text-muted">Minimum 8 characters</small>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                    <select class="form-control" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="age" class="form-label">Age <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="age" name="age" min="18" max="80" required>
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
                        </div>

                        <!-- Contact Information -->
                        <hr class="my-4">
                        <h5 class="text-gray-800 mb-3">Contact Information</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label">Primary Phone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_number" class="form-label">Alternative Phone</label>
                                    <input type="tel" class="form-control" id="contact_number" name="contact_number">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Professional Information -->
                        <hr class="my-4">
                        <h5 class="text-gray-800 mb-3">Professional Information</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="position" class="form-label">Position</label>
                                    <input type="text" class="form-control" id="position" name="position" placeholder="e.g., Mathematics Teacher">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="department" class="form-label">Department</label>
                                    <select class="form-control" id="department" name="department">
                                        <option value="">Select Department</option>
                                        <option value="Mathematics">Mathematics</option>
                                        <option value="Science">Science</option>
                                        <option value="English">English</option>
                                        <option value="Filipino">Filipino</option>
                                        <option value="Social Studies">Social Studies</option>
                                        <option value="Physical Education">Physical Education</option>
                                        <option value="Arts">Arts</option>
                                        <option value="Technology">Technology</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="Active" selected>Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <hr class="my-4">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Add Teacher
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Profile Picture Upload -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profile Picture</h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img id="imagePreview" src="{{ asset('images/user.png') }}" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                    <div class="form-group">
                        <label for="image" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-camera me-2"></i>Choose Photo
                        </label>
                        <input type="file" class="d-none" id="image" name="image" accept="image/*">
                        <div class="invalid-feedback"></div>
                    </div>
                    <small class="text-muted">Max size: 2MB. Formats: JPG, PNG, GIF</small>
                </div>
            </div>
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
                <p class="mb-0">Teacher has been added successfully!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="redirectToIndex()">Go to Teachers List</button>
                <button type="button" class="btn btn-primary" onclick="resetForm()">Add Another Teacher</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('teacherForm');
    const submitBtn = document.getElementById('submitBtn');
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');

    // Image preview
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
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
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding Teacher...';
        
        const formData = new FormData(form);
        
        fetch('{{ route("admin.teachers.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(async response => {
            const ct = response.headers.get('content-type') || '';
            if (ct.includes('application/json')) return response.json();
            const text = await response.text();
            throw new Error(text);
        })
        .then(data => {
            if (data.success) {
                try {
                    const modalEl = document.getElementById('successModal');
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                    modal.show();
                } catch (e) { console.warn('Modal show error:', e); }
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
            console.error('Error:', error);
            console.error('Full error details:', error);
            alert('An error occurred while adding the teacher. Check console for details.');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Add Teacher';
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
    document.getElementById('teacherForm').reset();
    document.getElementById('imagePreview').src = '{{ asset("images/user.png") }}';
    
    // Clear password validation states
    document.getElementById('password').classList.remove('is-invalid');
    document.getElementById('password_confirmation').classList.remove('is-invalid');
    document.getElementById('password_confirmation').setCustomValidity('');
    
    try {
        const modalEl = document.getElementById('successModal');
        const modal = bootstrap.Modal.getInstance(modalEl) || bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.hide();
    } catch (e) { console.warn('Modal hide error:', e); }
}

function redirectToIndex() {
    window.location.href = '{{ route("admin.teachers.index") }}';
}
</script>
@endpush
