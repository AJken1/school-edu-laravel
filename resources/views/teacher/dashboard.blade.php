@extends('layouts.teacher')

@section('title', 'Teacher Dashboard - EDUgate')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-chalkboard-teacher me-2"></i>My Dashboard
            </h1>
            <p class="text-muted mb-0">Welcome back, {{ $teacherData['user']->name ?? 'Teacher' }}!</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-info" onclick="viewMyProfile()">
                <i class="fas fa-user me-2"></i>My Profile
            </button>
            <button class="btn btn-success" onclick="viewMyStatus()">
                <i class="fas fa-user-check me-2"></i>My Status
            </button>
            <a class="btn btn-warning" href="{{ route('teacher.subjects.index') }}">
                <i class="fas fa-book me-2"></i>Manage Subjects
            </a>
            <a class="btn btn-primary" href="{{ route('teacher.students.index') }}">
                <i class="fas fa-users me-2"></i>Manage Students
            </a>
            <a class="btn btn-warning" href="{{ route('teacher.files.index') }}">
                <i class="fas fa-file-alt me-2"></i>Files & Documents
            </a>
        </div>
    </div>


    <!-- Action Cards Row - Similar to Student Portal -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2 clickable-card" onclick="window.location.href='{{ route('teacher.students.index') }}'">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">My Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['studentCount'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2 clickable-card" onclick="window.location.href='{{ route('teacher.subjects.index') }}'">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">My Subjects</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['subjectCount'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-warning shadow h-100 py-2 clickable-card" onclick="window.location.href='{{ route('teacher.files.index') }}'">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Files & Documents</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['totalFiles'] ?? 0 }} Files</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-info shadow h-100 py-2 clickable-card" onclick="viewMyStatus()">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">My Status</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Active</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Personal Information & Recent Files -->
    <div class="row mb-4">
        @if($teacherData['teacher'])
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chalkboard-teacher me-2"></i>My Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-user me-2"></i>Full Name:</strong> 
                                {{ $teacherData['teacher']->first_name ?? $teacherData['teacher']->firstname }} 
                                {{ $teacherData['teacher']->last_name ?? $teacherData['teacher']->lastname }}
                            </p>
                            <p><strong><i class="fas fa-envelope me-2"></i>Email:</strong> 
                                {{ $teacherData['user']->email }}
                            </p>
                            <p><strong><i class="fas fa-phone me-2"></i>Contact:</strong> 
                                {{ $teacherData['teacher']->contact_number ?? 'Not provided' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-id-card me-2"></i>Employee ID:</strong> 
                                {{ $teacherData['teacher']->employee_id ?? 'Not assigned' }}
                            </p>
                            <p><strong><i class="fas fa-calendar me-2"></i>Employment Date:</strong> 
                                {{ $teacherData['employment_date'] ? $teacherData['employment_date']->format('M d, Y') : 'Unknown' }}
                            </p>
                            <p><strong><i class="fas fa-graduation-cap me-2"></i>Department:</strong> 
                                {{ $teacherData['department'] ?? 'Not assigned' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-body text-center py-5">
                    <div class="text-muted">
                        <i class="fas fa-exclamation-circle fa-3x mb-3 text-warning"></i>
                        <h5>No Teacher Record Found</h5>
                        <p>Your account doesn't have a teacher record associated with it yet. Please contact the administrator to set up your teacher profile.</p>
                        <a href="{{ route('profile.show') }}" class="btn btn-primary">
                            <i class="fas fa-user me-2"></i>View Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        @if($teacherData['teacher'])
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-file-alt me-2"></i>Recent Student Files
                    </h6>
                </div>
                <div class="card-body">
                    @if(is_countable($recentFiles) && count($recentFiles) > 0)
                        @foreach($recentFiles as $file)
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-file fa-lg text-primary me-2"></i>
                            <div class="flex-grow-1">
                                <small class="d-block">{{ $file->file_name }}</small>
                                <small class="text-muted">{{ $file->student->first_name ?? $file->student->firstname }} {{ $file->student->last_name ?? $file->student->lastname }}</small>
                            </div>
                            <span class="badge badge-{{ $file->status == 'approved' ? 'success' : ($file->status == 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($file->status) }}
                            </span>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-folder-open fa-2x mb-2"></i>
                            <p>No files uploaded yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- My Students -->
    @if(is_countable($students) && count($students) > 0)
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-users me-2"></i>My Students
            </h6>
            <div>
                <span class="badge badge-secondary">{{ is_countable($students) ? count($students) : 0 }} students</span>
                <a href="{{ route('teacher.students.index') }}" class="btn btn-sm btn-primary ms-2">
                    <i class="fas fa-eye me-1"></i>View All
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($students as $student)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 50px; height: 50px;">
                                {{ strtoupper(substr($student->first_name ?? $student->firstname ?? 'S', 0, 1)) }}
                            </div>
                            <h6 class="card-title mb-1">
                                {{ $student->first_name ?? $student->firstname }} 
                                {{ $student->last_name ?? $student->lastname }}
                            </h6>
                            <small class="text-muted">
                                Student ID: {{ $student->id }}
                            </small>
                            @if($student->user && $student->user->email)
                            <div class="mt-2">
                                <small class="text-muted">{{ $student->user->email }}</small>
                            </div>
                            @endif
                            <div class="mt-2">
                                <span class="badge badge-info">{{ $student->grade_level ? 'Grade ' . $student->grade_level : ($student->grade ?? 'No Grade') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <div class="card shadow">
        <div class="card-body text-center py-5">
            <div class="text-muted">
                <i class="fas fa-users fa-3x mb-3"></i>
                <h5>No Students Found</h5>
                <p>There are no active students in the system yet.</p>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Teacher Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-check me-2"></i>My Teaching Status
                </h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="statusModalBody">
                <!-- Status details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Clean, modern dashboard styling */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
    border-radius: 12px 12px 0 0 !important;
    font-weight: 600;
}

.border-left-primary {
    border-left: 4px solid #4e73df !important;
}

.border-left-success {
    border-left: 4px solid #1cc88a !important;
}

.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}

.border-left-info {
    border-left: 4px solid #36b9cc !important;
}

.text-xs {
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.h5 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
}

.text-gray-300 {
    color: #a0aec0 !important;
}

.text-gray-800 {
    color: #2d3748 !important;
}

.text-muted {
    color: #718096 !important;
}

/* Compact spacing */
.mb-4 {
    margin-bottom: 1.5rem !important;
}

.py-2 {
    padding-top: 0.75rem !important;
    padding-bottom: 0.75rem !important;
}

/* Button styling */
.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Badge styling */
.badge {
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
}

/* Icon styling */
.fas, .far, .fab {
    font-weight: 600;
}

/* Clickable card styling */
.clickable-card {
    cursor: pointer;
    transition: all 0.3s ease;
}

.clickable-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
    border-left-width: 6px !important;
}

.clickable-card:active {
    transform: translateY(-1px);
}

/* Enhanced card hover effects */
.clickable-card .card-body {
    transition: all 0.3s ease;
}

.clickable-card:hover .card-body {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

.clickable-card:hover .fas {
    transform: scale(1.1);
    transition: transform 0.3s ease;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .h5 {
        font-size: 1.25rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .clickable-card:hover {
        transform: translateY(-2px);
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-load any necessary data
});

function viewMyProfile() {
    window.location.href = '{{ route("profile.show") }}';
}

function viewMyStatus() {
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    document.getElementById('statusModalBody').innerHTML = `
        <div class="text-center py-3">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2">Loading your teaching status...</p>
        </div>
    `;
    modal.show();
    
    // Simulate loading personal status
    setTimeout(() => {
        document.getElementById('statusModalBody').innerHTML = `
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="alert alert-success">
                        <h5><i class="fas fa-check-circle me-2"></i>Teaching Status: Active</h5>
                        <p class="mb-0">You are an active teacher with full access to student management.</p>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">Current Assignment</h6>
                        </div>
                        <div class="card-body">
                            <p><strong>Department:</strong> {{ $teacherData['department'] ?? 'Not Assigned' }}</p>
                            <p><strong>Employee ID:</strong> {{ $teacherData['teacher']->employee_id ?? 'Not Assigned' }}</p>
                            <p><strong>Status:</strong> <span class="badge badge-success">Active</span></p>
                            <p><strong>Employment Date:</strong> {{ $teacherData['employment_date'] ? $teacherData['employment_date']->format('M d, Y') : 'Unknown' }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">Access Permissions</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>View All Students
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>Edit Student Information
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>Manage Subjects
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>Upload Files
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>View Student Documents
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12 mt-3">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('teacher.students.index') }}" class="btn btn-primary">
                                    <i class="fas fa-users me-2"></i>Manage Students
                                </a>
                                <a href="{{ route('teacher.subjects.index') }}" class="btn btn-success">
                                    <i class="fas fa-book me-2"></i>Manage Subjects
                                </a>
                                <a href="{{ route('teacher.files.index') }}" class="btn btn-warning">
                                    <i class="fas fa-file-alt me-2"></i>View Files
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }, 1000);
}
</script>
@endpush

