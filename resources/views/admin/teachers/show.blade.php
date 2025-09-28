@extends('layouts.admin')

@section('title', 'Teacher Profile - EDUgate Admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center py-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Teacher Profile</h1>
            <p class="text-muted mb-0">View detailed teacher information</p>
        </div>
        <div>
            <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-2"></i>Edit Profile
            </a>
            <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Teachers
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Profile Overview -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <div class="mb-4">
                        @if($teacher->profile_picture)
                            <img src="{{ asset('storage/' . $teacher->profile_picture) }}" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 150px; height: 150px; font-size: 3rem;">
                                {{ strtoupper(substr($teacher->firstname, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <h4 class="mb-1">{{ $teacher->firstname }} {{ $teacher->lastname }}</h4>
                    <p class="text-muted mb-2">{{ $teacher->position ?: 'Teacher' }}</p>
                    @if($teacher->employee_id)
                        <p class="text-muted mb-3">ID: {{ $teacher->employee_id }}</p>
                    @endif
                    
                    <div class="row text-center">
                        <div class="col">
                            <span class="badge badge-{{ $teacher->status == 'Active' ? 'success' : 'secondary' }} badge-lg">
                                {{ $teacher->status }}
                            </span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Quick Stats -->
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-right">
                                <h5 class="mb-0">{{ $teacher->subjects->count() }}</h5>
                                <small class="text-muted">Subjects</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h5 class="mb-0">{{ $teacher->created_at->diffForHumans() }}</h5>
                            <small class="text-muted">Joined</small>
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
                            {{ $teacher->email ?: 'Not provided' }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Primary Phone</label>
                        <div class="font-weight-bold">
                            <i class="fas fa-phone text-muted me-2"></i>
                            {{ $teacher->phone ?: 'Not provided' }}
                        </div>
                    </div>
                    @if($teacher->contact_number)
                        <div class="mb-3">
                            <label class="text-muted small">Alternative Phone</label>
                            <div class="font-weight-bold">
                                <i class="fas fa-mobile-alt text-muted me-2"></i>
                                {{ $teacher->contact_number }}
                            </div>
                        </div>
                    @endif
                    <div class="mb-0">
                        <label class="text-muted small">Address</label>
                        <div class="font-weight-bold">
                            <i class="fas fa-map-marker-alt text-muted me-2"></i>
                            {{ $teacher->address ?: 'Not provided' }}
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
                            <div class="font-weight-bold">{{ $teacher->firstname }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Last Name</label>
                            <div class="font-weight-bold">{{ $teacher->lastname }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Gender</label>
                            <div class="font-weight-bold">{{ $teacher->gender ?: 'Not specified' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Age</label>
                            <div class="font-weight-bold">{{ $teacher->age ?: 'Not specified' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Date of Birth</label>
                            <div class="font-weight-bold">
                                {{ $teacher->date_of_birth ? $teacher->date_of_birth->format('M d, Y') : 'Not provided' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">User Account</label>
                            <div class="font-weight-bold">
                                {{ $teacher->user->user_id ?? 'Not linked' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Professional Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Professional Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Employee ID</label>
                            <div class="font-weight-bold">{{ $teacher->employee_id ?: 'Not assigned' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Position</label>
                            <div class="font-weight-bold">{{ $teacher->position ?: 'Not specified' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Department</label>
                            <div class="font-weight-bold">{{ $teacher->department ?: 'Not assigned' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Status</label>
                            <div>
                                <span class="badge badge-{{ $teacher->status == 'Active' ? 'success' : 'secondary' }}">
                                    {{ $teacher->status }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Date Joined</label>
                            <div class="font-weight-bold">{{ $teacher->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Last Updated</label>
                            <div class="font-weight-bold">{{ $teacher->updated_at->format('M d, Y g:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subjects Taught -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Subjects Taught</h6>
                    <span class="badge badge-primary">{{ $teacher->subjects->count() }} Subjects</span>
                </div>
                <div class="card-body">
                    @if($teacher->subjects->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Grade Level</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teacher->subjects as $subject)
                                        <tr>
                                            <td>{{ $subject->name }}</td>
                                            <td>{{ $subject->grade_level ?: 'Not specified' }}</td>
                                            <td>
                                                <span class="badge badge-success">Active</span>
                                            </td>
                                            <td>{{ $subject->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Subjects Assigned</h5>
                            <p class="text-muted">This teacher hasn't been assigned any subjects yet.</p>
                        </div>
                    @endif
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
                            <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-warning btn-block">
                                <i class="fas fa-edit me-2"></i>Edit Profile
                            </a>
                        </div>
                        <div class="col-md-6 mb-2">
                            <button type="button" class="btn btn-danger btn-block" onclick="deleteTeacher()">
                                <i class="fas fa-trash me-2"></i>Delete Teacher
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
                <p>Are you sure you want to delete this teacher?</p>
                <p class="text-danger"><strong>This action cannot be undone!</strong></p>
                <p class="text-muted">This will also delete:</p>
                <ul class="text-muted">
                    <li>Teacher's user account</li>
                    <li>Associated subjects ({{ $teacher->subjects->count() }})</li>
                    <li>Profile picture</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete Teacher</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteTeacher() {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function confirmDelete() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.teachers.destroy", $teacher) }}';
    
    // Add CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);
    
    // Add method override
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    form.appendChild(methodInput);
    
    document.body.appendChild(form);
    form.submit();
}

function resetPassword() {
    if (confirm('Are you sure you want to reset this teacher\'s password? They will need to use their date of birth (ddmmyyyy format) as the new password.')) {
        // Implement password reset functionality
        alert('Password reset functionality will be implemented');
    }
}

function printProfile() {
    window.print();
}
</script>
@endpush
