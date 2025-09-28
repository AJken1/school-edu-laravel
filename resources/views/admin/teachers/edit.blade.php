@extends('layouts.admin')

@section('title', 'Edit Teacher - EDUgate Admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center py-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Edit Teacher</h1>
            <p class="text-muted mb-0">Update teacher profile information</p>
        </div>
        <div>
            <a href="{{ route('admin.teachers.show', $teacher) }}" class="btn btn-info me-2">
                <i class="fas fa-eye me-2"></i>View Profile
            </a>
            <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Teachers
            </a>
        </div>
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
                        @method('PUT')
                        
                        <!-- Personal Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firstname" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" value="{{ $teacher->firstname }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lastname" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" value="{{ $teacher->lastname }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="employee_id" class="form-label">Employee ID</label>
                                    <input type="text" class="form-control" id="employee_id" name="employee_id" value="{{ $teacher->employee_id }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $teacher->email }}" required>
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
                                        <option value="Male" {{ $teacher->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ $teacher->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="age" class="form-label">Age <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="age" name="age" min="18" max="80" value="{{ $teacher->age }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ $teacher->date_of_birth ? $teacher->date_of_birth->format('Y-m-d') : '' }}" required>
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
                                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ $teacher->phone }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_number" class="form-label">Alternative Phone</label>
                                    <input type="tel" class="form-control" id="contact_number" name="contact_number" value="{{ $teacher->contact_number }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="3" required>{{ $teacher->address }}</textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Professional Information -->
                        <hr class="my-4">
                        <h5 class="text-gray-800 mb-3">Professional Information</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="position" class="form-label">Position</label>
                                    <input type="text" class="form-control" id="position" name="position" value="{{ $teacher->position }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="department" class="form-label">Department</label>
                                    <select class="form-control" id="department" name="department">
                                        <option value="">Select Department</option>
                                        <option value="Mathematics" {{ $teacher->department == 'Mathematics' ? 'selected' : '' }}>Mathematics</option>
                                        <option value="Science" {{ $teacher->department == 'Science' ? 'selected' : '' }}>Science</option>
                                        <option value="English" {{ $teacher->department == 'English' ? 'selected' : '' }}>English</option>
                                        <option value="Filipino" {{ $teacher->department == 'Filipino' ? 'selected' : '' }}>Filipino</option>
                                        <option value="Social Studies" {{ $teacher->department == 'Social Studies' ? 'selected' : '' }}>Social Studies</option>
                                        <option value="Physical Education" {{ $teacher->department == 'Physical Education' ? 'selected' : '' }}>Physical Education</option>
                                        <option value="Arts" {{ $teacher->department == 'Arts' ? 'selected' : '' }}>Arts</option>
                                        <option value="Technology" {{ $teacher->department == 'Technology' ? 'selected' : '' }}>Technology</option>
                                        <option value="Other" {{ $teacher->department == 'Other' ? 'selected' : '' }}>Other</option>
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
                                        <option value="Active" {{ $teacher->status == 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $teacher->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                                <i class="fas fa-save me-2"></i>Update Teacher
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
                        @if($teacher->profile_picture)
                            <img id="imagePreview" src="{{ asset('storage/' . $teacher->profile_picture) }}" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <img id="imagePreview" src="{{ asset('images/user.png') }}" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="image" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-camera me-2"></i>Change Photo
                        </label>
                        <input type="file" class="d-none" id="image" name="image" accept="image/*" form="teacherForm">
                        <div class="invalid-feedback"></div>
                    </div>
                    @if($teacher->profile_picture)
                        <button type="button" class="btn btn-outline-danger btn-sm mt-2" onclick="removeProfilePicture()">
                            <i class="fas fa-trash me-2"></i>Remove Photo
                        </button>
                    @endif
                    <small class="text-muted d-block mt-2">Max size: 2MB. Formats: JPG, PNG, GIF</small>
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
                <p class="mb-0">Teacher information has been updated successfully!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="redirectToIndex()">Go to Teachers List</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Continue Editing</button>
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

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating Teacher...';
        
        const formData = new FormData(form);
        
        fetch('{{ route("admin.teachers.update", $teacher) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
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
                const modal = new bootstrap.Modal(document.getElementById('successModal'));
                modal.show();
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
            alert('An error occurred while updating the teacher');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Update Teacher';
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

function removeProfilePicture() {
    if (confirm('Are you sure you want to remove the profile picture?')) {
        fetch('{{ route("admin.teachers.index") }}/{{ $teacher->id }}/remove-picture', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('imagePreview').src = '{{ asset("images/user.png") }}';
                location.reload(); // Refresh to update the remove button visibility
            } else {
                alert('Error removing profile picture');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    }
}

function redirectToIndex() {
    window.location.href = '{{ route("admin.teachers.index") }}';
}
</script>
@endpush

