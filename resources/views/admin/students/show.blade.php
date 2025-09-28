@extends('layouts.admin')

@section('title', 'Student Profile - EDUgate Admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center py-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Student Profile</h1>
            <p class="text-muted mb-0">View detailed student information</p>
        </div>
        <div>
            <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-2"></i>Edit Profile
            </a>
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Students
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Profile Overview -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 150px; height: 150px; font-size: 3rem;">
                            {{ strtoupper(substr(($student->first_name ?: $student->firstname), 0, 1)) }}
                        </div>
                    </div>
                    <h4 class="mb-1">
                        {{ ($student->first_name && $student->last_name) ? $student->first_name . ' ' . $student->last_name : ($student->firstname . ' ' . $student->lastname) }}
                    </h4>
                    @if($student->mi)
                        <p class="text-muted mb-2">Middle Initial: {{ $student->mi }}</p>
                    @endif
                    @if($student->lrn_number)
                        <p class="text-muted mb-3">LRN: {{ $student->lrn_number }}</p>
                    @endif
                    
                    <div class="row text-center">
                        <div class="col">
                            <span class="badge badge-{{ $student->status == 'Active' ? 'success' : ($student->status == 'enrolled' ? 'primary' : ($student->status == 'submitted' ? 'warning' : 'secondary')) }} badge-lg">
                                {{ $student->status }}
                            </span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Quick Stats -->
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-right">
                                <h5 class="mb-0">
                                    @if($student->grade_level)
                                        {{ $student->grade_level }}
                                    @elseif($student->grade)
                                        {{ $student->grade }}
                                    @else
                                        -
                                    @endif
                                </h5>
                                <small class="text-muted">Grade</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h5 class="mb-0">{{ $student->created_at->diffForHumans() }}</h5>
                            <small class="text-muted">Enrolled</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Contact Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Email</label>
                        <div class="font-weight-bold">
                            <i class="fas fa-envelope text-muted me-2"></i>
                            {{ $student->email ?: 'Not provided' }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Contact Number</label>
                        <div class="font-weight-bold">
                            <i class="fas fa-phone text-muted me-2"></i>
                            {{ $student->contact_number ?: 'Not provided' }}
                        </div>
                    </div>
                    @if($student->parent_phone)
                        <div class="mb-3">
                            <label class="text-muted small">Parent Phone</label>
                            <div class="font-weight-bold">
                                <i class="fas fa-mobile-alt text-muted me-2"></i>
                                {{ $student->parent_phone }}
                            </div>
                        </div>
                    @endif
                    <div class="mb-0">
                        <label class="text-muted small">Address</label>
                        <div class="font-weight-bold">
                            <i class="fas fa-map-marker-alt text-muted me-2"></i>
                            {{ $student->current_address ?: $student->address ?: 'Not provided' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Information -->
        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Personal Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">First Name</label>
                            <div class="font-weight-bold">{{ $student->first_name ?: $student->firstname ?: 'Not provided' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Last Name</label>
                            <div class="font-weight-bold">{{ $student->last_name ?: $student->lastname ?: 'Not provided' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Middle Initial</label>
                            <div class="font-weight-bold">{{ $student->mi ?: 'Not provided' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Sex/Gender</label>
                            <div class="font-weight-bold">{{ $student->sex ?: $student->gender ?: 'Not specified' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Date of Birth</label>
                            <div class="font-weight-bold">
                                {{ $student->date_of_birth ? $student->date_of_birth->format('M d, Y') : 'Not provided' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Religion</label>
                            <div class="font-weight-bold">{{ $student->religion ?: 'Not specified' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Academic Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">LRN Number</label>
                            <div class="font-weight-bold">{{ $student->lrn_number ?: 'Not assigned' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Application ID</label>
                            <div class="font-weight-bold">{{ $student->application_id ?: 'Not assigned' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">School Year</label>
                            <div class="font-weight-bold">{{ $student->school_year ?: 'Not specified' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Grade Level</label>
                            <div class="font-weight-bold">
                                @if($student->grade_level)
                                    Grade {{ $student->grade_level }}
                                @elseif($student->grade)
                                    {{ $student->grade }}
                                @else
                                    Not specified
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Previous School</label>
                            <div class="font-weight-bold">{{ $student->previous_school ?: 'Not provided' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Enrollment Status</label>
                            <div>
                                <span class="badge badge-{{ $student->status == 'Active' ? 'success' : ($student->status == 'enrolled' ? 'primary' : ($student->status == 'submitted' ? 'warning' : 'secondary')) }}">
                                    {{ $student->status }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Date Enrolled</label>
                            <div class="font-weight-bold">{{ $student->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Last Updated</label>
                            <div class="font-weight-bold">{{ $student->updated_at->format('M d, Y g:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Parent/Guardian Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Parent/Guardian Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Father's Information -->
                        @if($student->father_firstname || $student->father_lastname)
                        <div class="col-12 mb-3">
                            <h6 class="text-muted">Father's Information</h6>
                            <div class="font-weight-bold">
                                {{ $student->father_firstname }} {{ $student->father_mi }} {{ $student->father_lastname }}
                            </div>
                        </div>
                        @endif

                        <!-- Mother's Information -->
                        @if($student->mother_firstname || $student->mother_lastname)
                        <div class="col-12 mb-3">
                            <h6 class="text-muted">Mother's Information</h6>
                            <div class="font-weight-bold">
                                {{ $student->mother_firstname }} {{ $student->mother_mi }} {{ $student->mother_lastname }}
                            </div>
                        </div>
                        @endif

                        <!-- Guardian's Information -->
                        @if($student->guardian_firstname || $student->guardian_lastname)
                        <div class="col-12 mb-3">
                            <h6 class="text-muted">Guardian's Information</h6>
                            <div class="font-weight-bold">
                                {{ $student->guardian_firstname }} {{ $student->guardian_mi }} {{ $student->guardian_lastname }}
                            </div>
                        </div>
                        @endif

                        <!-- General Parent Info -->
                        @if($student->parent_name)
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Parent/Guardian Name</label>
                            <div class="font-weight-bold">{{ $student->parent_name }}</div>
                        </div>
                        @endif

                        @if($student->parent_email)
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Parent Email</label>
                            <div class="font-weight-bold">{{ $student->parent_email }}</div>
                        </div>
                        @endif

                        @if($student->relationship)
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Relationship</label>
                            <div class="font-weight-bold">{{ $student->relationship }}</div>
                        </div>
                        @endif

                        @if(!$student->father_firstname && !$student->mother_firstname && !$student->guardian_firstname && !$student->parent_name)
                        <div class="col-12">
                            <div class="text-center py-3">
                                <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No parent/guardian information provided</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Special Needs & Additional Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Special Needs & Additional Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">Person with Disability (PWD)</label>
                            <div class="font-weight-bold">
                                @if($student->pwd)
                                    <span class="badge badge-info">Yes</span>
                                    @if($student->pwd_details)
                                        <div class="mt-2">
                                            <small class="text-muted">Details:</small>
                                            <div>{{ $student->pwd_details }}</div>
                                        </div>
                                    @endif
                                @else
                                    <span class="badge badge-secondary">No</span>
                                @endif
                            </div>
                        </div>

                        @if($student->medical_conditions)
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">Medical Conditions</label>
                            <div class="font-weight-bold">{{ $student->medical_conditions }}</div>
                        </div>
                        @endif

                        @if($student->additional_notes)
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">Additional Notes</label>
                            <div class="font-weight-bold">{{ $student->additional_notes }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-warning btn-block">
                                <i class="fas fa-edit me-2"></i>Edit Profile
                            </a>
                        </div>
                        <div class="col-md-6 mb-2">
                            <button type="button" class="btn btn-danger btn-block" onclick="deleteStudent()">
                                <i class="fas fa-trash me-2"></i>Delete Student
                            </button>
                        </div>
                        <div class="col-md-6 mb-2">
                            <button type="button" class="btn btn-info btn-block" onclick="resetPassword()">
                                <i class="fas fa-key me-2"></i>Reset Password
                            </button>
                        </div>
                        <div class="col-md-6 mb-2">
                            <button type="button" class="btn btn-secondary btn-block" onclick="printProfile()">
                                <i class="fas fa-print me-2"></i>Print Profile
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Warning!</strong> This action cannot be undone.
                </div>
                <p>Are you sure you want to delete <strong>{{ ($student->first_name && $student->last_name) ? $student->first_name . ' ' . $student->last_name : ($student->firstname . ' ' . $student->lastname) }}</strong>?</p>
                <p class="text-muted">This will permanently remove:</p>
                <ul class="text-muted">
                    <li>Student's user account</li>
                    <li>All enrollment records</li>
                    <li>Associated academic data</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete Student</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteStudent() {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

async function confirmDelete() {
    const deleteBtn = document.querySelector('#deleteModal .btn-danger');
    const originalText = deleteBtn.innerHTML;
    
    try {
        // Show loading state
        deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
        deleteBtn.disabled = true;
        
        const response = await fetch('{{ route("admin.students.destroy", $student) }}', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Close modal using Bootstrap 5
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
            modal.hide();
            
            // Show success message and redirect
            showAlert('success', result.message);
            
            // Redirect to students list after a short delay
            setTimeout(() => {
                window.location.href = '{{ route("admin.students.index") }}';
            }, 1500);
        } else {
            showAlert('error', result.message || 'Failed to delete student');
        }
    } catch (error) {
        console.error('Delete error:', error);
        showAlert('error', 'An error occurred while deleting the student');
    } finally {
        // Reset button state
        deleteBtn.innerHTML = originalText;
        deleteBtn.disabled = false;
    }
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="fas ${icon} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    const alertContainer = document.getElementById('alert-container') || createAlertContainer();
    alertContainer.innerHTML = alertHtml;
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        const alert = alertContainer.querySelector('.alert');
        if (alert) {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 150);
        }
    }, 5000);
}

function createAlertContainer() {
    const container = document.createElement('div');
    container.id = 'alert-container';
    container.style.position = 'fixed';
    container.style.top = '20px';
    container.style.right = '20px';
    container.style.zIndex = '9999';
    container.style.width = '400px';
    document.body.appendChild(container);
    return container;
}

function resetPassword() {
    if (confirm('Are you sure you want to reset this student\'s password? They will need to use their date of birth (ddmmyyyy format) as the new password.')) {
        // Implement password reset functionality
        alert('Password reset functionality will be implemented');
    }
}

function printProfile() {
    window.print();
}
</script>
@endpush
