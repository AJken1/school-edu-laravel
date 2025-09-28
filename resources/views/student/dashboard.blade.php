@extends('layouts.student')

@section('title', 'Student Dashboard - EDUgate')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tachometer-alt me-2"></i>My Dashboard
            </h1>
            <p class="text-muted mb-0">Welcome back, {{ $studentData['user']->name ?? 'Student' }}!</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-info" onclick="viewMyProfile()">
                <i class="fas fa-user me-2"></i>My Profile
            </button>
            <button class="btn btn-success" onclick="checkMyStatus()">
                <i class="fas fa-user-check me-2"></i>My Status
            </button>
            <a class="btn btn-warning" href="{{ route('student.enrollment.edit') }}">
                <i class="fas fa-user-edit me-2"></i>Edit Enrollment Info
            </a>
            <a class="btn btn-primary" href="{{ route('student.files.index') }}">
                <i class="fas fa-file-alt me-2"></i>My Documents
            </a>
        </div>
    </div>

    <!-- Student Info Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">My Grade</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $studentData['grade'] ? 'Grade ' . $studentData['grade'] : 'Not Assigned' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-layer-group fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Status</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ ucfirst($studentData['status']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            @if($studentData['status'] == 'Active')
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            @elseif($studentData['status'] == 'pending')
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            @else
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">My Documents</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ is_countable($documents) ? count($documents) : 0 }} Files
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Classmates</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ is_countable($classmates) ? count($classmates) : 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Personal Information & Documents -->
    <div class="row mb-4">
        @if($studentData['student'])
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user me-2"></i>My Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-user me-2"></i>Full Name:</strong> 
                                {{ $studentData['student']->first_name ?? $studentData['student']->firstname }} 
                                {{ $studentData['student']->last_name ?? $studentData['student']->lastname }}
                            </p>
                            <p><strong><i class="fas fa-envelope me-2"></i>Email:</strong> 
                                {{ $studentData['user']->email }}
                            </p>
                            <p><strong><i class="fas fa-phone me-2"></i>Contact:</strong> 
                                {{ $studentData['student']->contact_number ?? 'Not provided' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-id-card me-2"></i>Student ID:</strong> 
                                {{ $studentData['student']->id ?? 'Not assigned' }}
                            </p>
                            <p><strong><i class="fas fa-calendar me-2"></i>Enrollment Date:</strong> 
                                {{ $studentData['enrollment_date'] ? $studentData['enrollment_date']->format('M d, Y') : 'Unknown' }}
                            </p>
                            <p><strong><i class="fas fa-home me-2"></i>Address:</strong> 
                                {{ $studentData['student']->current_address ?? $studentData['student']->address ?? 'Not provided' }}
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
                        <h5>No Student Record Found</h5>
                        <p>Your account doesn't have a student record associated with it yet. Please contact the administrator to set up your student profile.</p>
                        <a href="{{ route('profile.show') }}" class="btn btn-primary">
                            <i class="fas fa-user me-2"></i>View Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        @if($studentData['student'])
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-file-alt me-2"></i>My Documents
                    </h6>
                </div>
                <div class="card-body">
                    @if(is_countable($documents) && count($documents) > 0)
                        @foreach($documents as $document)
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-file fa-lg text-primary me-2"></i>
                            <div class="flex-grow-1">
                                <small class="d-block">{{ $document->file_name }}</small>
                                <small class="text-muted">{{ $document->created_at->format('M d, Y') }}</small>
                            </div>
                            <span class="badge badge-{{ $document->status == 'approved' ? 'success' : ($document->status == 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($document->status) }}
                            </span>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-folder-open fa-2x mb-2"></i>
                            <p>No documents uploaded yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- My Classmates -->
    @if(is_countable($classmates) && count($classmates) > 0)
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-users me-2"></i>My Classmates
                @if($studentData['grade'])
                    <span class="badge badge-info ms-2">Grade {{ $studentData['grade'] }}</span>
                @endif
            </h6>
            <div>
                <span class="badge badge-secondary">{{ is_countable($classmates) ? count($classmates) : 0 }} classmates</span>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($classmates as $classmate)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 50px; height: 50px;">
                                {{ strtoupper(substr($classmate->first_name ?? $classmate->firstname ?? 'S', 0, 1)) }}
                            </div>
                            <h6 class="card-title mb-1">
                                {{ $classmate->first_name ?? $classmate->firstname }} 
                                {{ $classmate->last_name ?? $classmate->lastname }}
                            </h6>   
                            @if($classmate->user && $classmate->user->email)
                            <div class="mt-2">
                                <small class="text-muted">{{ $classmate->user->email }}</small>
                            </div>
                            @endif
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
                <h5>No Classmates Found</h5>
                <p>You don't have any classmates in your grade level yet.</p>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Student Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>Student Status Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="statusModalBody">
                <!-- Status details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- My Status Modal -->
<div class="modal fade" id="myStatusModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-check me-2"></i>My Academic Status
                </h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="myStatusModalBody">
                <!-- My status details will be loaded here -->
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

/* Responsive adjustments */
@media (max-width: 768px) {
    .h5 {
        font-size: 1.25rem;
    }
    
    .card-body {
        padding: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-load directory stats and list for old UI
    loadStudents();
});

function viewMyProfile() {
    window.location.href = '{{ route("profile.show") }}';
}

function checkMyStatus() {
    const modal = new bootstrap.Modal(document.getElementById('myStatusModal'));
    document.getElementById('myStatusModalBody').innerHTML = `
        <div class="text-center py-3">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2">Loading your academic status...</p>
        </div>
    `;
    modal.show();
    
    // Simulate loading personal status
    setTimeout(() => {
        document.getElementById('myStatusModalBody').innerHTML = `
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="alert alert-success">
                        <h5><i class="fas fa-check-circle me-2"></i>Academic Standing: Good</h5>
                        <p class="mb-0">You are in good academic standing with no pending requirements.</p>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">Current Enrollment</h6>
                        </div>
                        <div class="card-body">
                            <p><strong>Grade:</strong> {{ auth()->user()->student->grade ?? '11' }}</p>
                            <p><strong>Section:</strong> Diamond</p>
                            <p><strong>School Year:</strong> 2024-2025</p>
                            <p><strong>Status:</strong> <span class="badge badge-success">Active</span></p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">Document Status</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>Enrollment Form
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>Birth Certificate
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>Report Card
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-exclamation-triangle text-warning me-2"></i>Medical Certificate (Pending)
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12 mt-3">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">Next Steps</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><i class="fas fa-clock text-warning me-2"></i>Submit Medical Certificate by March 15, 2024</li>
                                <li class="mb-2"><i class="fas fa-info-circle text-info me-2"></i>Attend orientation on March 20, 2024</li>
                                <li><i class="fas fa-calendar text-primary me-2"></i>Classes begin March 25, 2024</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }, 1000);
}

// Old UI loader compatible: fetch from student route returning JSON
async function loadStudents(page = 1) {
    try {
        const url = new URL('{{ route('student.students.index') }}', window.location.origin);
        url.searchParams.set('page', page);
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();
        if (!data.success) throw new Error('Failed to load');
        // Update stat cards if present
        const total = document.getElementById('totalStudents');
        const active = document.getElementById('activeStudents');
        const pending = document.getElementById('pendingDocs');
        const graduated = document.getElementById('graduatedStudents');
        if (total) total.textContent = data.stats?.total ?? 0;
        if (active) active.textContent = data.stats?.active ?? 0;
        if (pending) pending.textContent = data.stats?.pending ?? 0;
        if (graduated) graduated.textContent = data.stats?.graduated ?? 0;
        // If old table exists, fill it
        const tbody = document.getElementById('studentsTableBody');
        if (tbody) {
            tbody.innerHTML = (data.students || []).map((s, idx) => `
                <tr>
                    <td class="text-center">${idx + 1}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    ${(s.first_name || s.firstname || 'S').charAt(0).toUpperCase()}
                                </div>
                            </div>
                            <div>
                                <div class="fw-bold">${(s.first_name || s.firstname || '')} ${(s.last_name || s.lastname || '')}</div>
                                <small class="text-muted">Student ID: ${s.id}</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge bg-secondary">${s.grade_level ? `Grade ${s.grade_level}` : (s.grade || 'Not Set')}</span></td>
                    <td><span class="badge bg-info">${s.status || ''}</span></td>
                    <td><small>${s.created_at ? new Date(s.created_at).toLocaleDateString() : ''}</small></td>
                    <td><small>${s.email || s.user?.email || ''}<br>${s.contact_number || ''}</small></td>
                    <td></td>
                </tr>
            `).join('');
            const info = document.getElementById('paginationInfo');
            if (info && data.pagination) {
                info.textContent = `Showing ${data.pagination.from || 0} to ${data.pagination.to || 0} of ${data.pagination.total || 0} students`;
            }
        }
    } catch (e) {
        console.error(e);
    }
}
</script>
@endpush

