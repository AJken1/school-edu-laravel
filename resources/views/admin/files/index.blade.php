@extends('layouts.admin')

@section('title', 'Files Management - EDUgate Admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center py-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Files Management</h1>
            <p class="text-muted mb-0">Manage student enrollment documents and track student status</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-success" onclick="exportFiles()">
                <i class="fas fa-download me-2"></i>Export Files Report
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_students'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_students'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Graduated</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['graduated_students'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Files</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_files'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Approved Files</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['approved_files'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Missing Files</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['missing_files'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Search & Filter</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.files.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by name, ID, LRN..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">All Student Status</option>
                            <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="enrolled" {{ request('status') == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                            <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>Graduated</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Application Submitted</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="file_status" class="form-control">
                            <option value="">All File Status</option>
                            <option value="pending" {{ request('file_status') == 'pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="approved" {{ request('file_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('file_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="missing" {{ request('file_status') == 'missing' ? 'selected' : '' }}>Missing</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Students Files Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Student Files Overview</h6>
            <div class="d-flex gap-2">
                <span class="badge badge-info">Total: {{ $students->total() }}</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="filesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Student Info</th>
                            <th>Status</th>
                            <th>Files Summary</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            @php
                                $totalFiles = $student->files->count();
                                $approvedFiles = $student->files->where('status', 'approved')->count();
                                $pendingFiles = $student->files->where('status', 'pending')->count();
                                $rejectedFiles = $student->files->where('status', 'rejected')->count();
                                $missingFiles = $student->files->where('status', 'missing')->count();
                                $completionPercentage = $totalFiles > 0 ? round(($approvedFiles / 7) * 100, 1) : 0; // 7 required files
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                                {{ strtoupper(substr(($student->first_name ?: $student->firstname), 0, 1)) }}{{ strtoupper(substr(($student->last_name ?: $student->lastname), 0, 1)) }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">
                                                {{ ($student->first_name && $student->last_name) ? $student->first_name . ' ' . $student->last_name : ($student->firstname . ' ' . $student->lastname) }}
                                            </div>
                                            <small class="text-muted">
                                                @if($student->application_id)
                                                    App ID: {{ $student->application_id }}
                                                @endif
                                                @if($student->lrn_number)
                                                    | LRN: {{ $student->lrn_number }}
                                                @endif
                                            </small>
                                            @if($student->grade_level || $student->grade)
                                                <br><small class="text-info">Grade: {{ $student->grade_level ?: $student->grade }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column align-items-start">
                                        @switch($student->status)
                                            @case('Active')
                                                <span class="badge badge-success mb-1">
                                                    <i class="fas fa-check-circle me-1"></i>Active
                                                </span>
                                                @break
                                            @case('enrolled')
                                                <span class="badge badge-primary mb-1">
                                                    <i class="fas fa-user-graduate me-1"></i>Enrolled
                                                </span>
                                                @break
                                            @case('graduated')
                                                <span class="badge badge-info mb-1">
                                                    <i class="fas fa-graduation-cap me-1"></i>Graduated
                                                </span>
                                                @break
                                            @case('inactive')
                                                <span class="badge badge-secondary mb-1">
                                                    <i class="fas fa-pause-circle me-1"></i>Inactive
                                                </span>
                                                @break
                                            @default
                                                <span class="badge badge-warning mb-1">
                                                    <i class="fas fa-clock me-1"></i>{{ ucfirst($student->status) }}
                                                </span>
                                        @endswitch
                                        <button class="btn btn-sm btn-outline-secondary" onclick="updateStudentStatus({{ $student->id }}, '{{ $student->status }}')">
                                            <i class="fas fa-edit"></i> Change
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <div class="mb-2">
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $completionPercentage }}%">
                                                {{ $completionPercentage }}% Complete
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between text-sm">
                                        <span class="badge badge-success">{{ $approvedFiles }} Approved</span>
                                        @if($pendingFiles > 0)
                                            <span class="badge badge-warning">{{ $pendingFiles }} Pending</span>
                                        @endif
                                        @if($rejectedFiles > 0)
                                            <span class="badge badge-danger">{{ $rejectedFiles }} Rejected</span>
                                        @endif
                                        @if($missingFiles > 0)
                                            <span class="badge badge-secondary">{{ $missingFiles }} Missing</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($student->files->count() > 0)
                                        {{ $student->files->max('updated_at')->format('M d, Y') }}
                                        <br><small class="text-muted">{{ $student->files->max('updated_at')->diffForHumans() }}</small>
                                    @else
                                        <span class="text-muted">No files yet</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group-vertical" role="group">
                                        <a href="{{ route('admin.files.show', $student) }}" class="btn btn-primary btn-sm mb-1">
                                            <i class="fas fa-folder-open me-1"></i>View Files
                                        </a>
                                        <a href="{{ route('admin.students.show', $student) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-user me-1"></i>Student Profile
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-3"></i>
                                        <h5>No students found</h5>
                                        <p>No students match your current search criteria.</p>
                                        <a href="{{ route('admin.files.index') }}" class="btn btn-primary">
                                            <i class="fas fa-refresh me-2"></i>Clear Filters
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($students->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Student Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Student Status</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="statusForm">
                    <div class="form-group">
                        <label>Student Status</label>
                        <select name="status" id="statusSelect" class="form-control">
                            <option value="Active">Active</option>
                            <option value="enrolled">Enrolled</option>
                            <option value="graduated">Graduated</option>
                            <option value="inactive">Inactive</option>
                            <option value="pending">Pending</option>
                            <option value="submitted">Application Submitted</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmStatusUpdate()">Update Status</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentStudentId = null;

function updateStudentStatus(studentId, currentStatus) {
    currentStudentId = studentId;
    document.getElementById('statusSelect').value = currentStatus;
    $('#statusModal').modal('show');
}

function confirmStatusUpdate() {
    if (currentStudentId) {
        const status = document.getElementById('statusSelect').value;
        
        fetch(`/admin/files/student/${currentStudentId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#statusModal').modal('hide');
                location.reload(); // Refresh to show updated status
            } else {
                alert('Error updating status. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating status. Please try again.');
        });
    }
}

function exportFiles() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'csv');
    window.location.href = "{{ route('admin.files.index') }}?" + params.toString();
}

// Realtime stats refresh every 30s
setInterval(() => {
    fetch("{{ route('admin.files.stats') }}")
        .then(resp => resp.json())
        .then(stats => {
            const updateText = (selector, nth, value) => {
                const nodes = document.querySelectorAll(selector);
                if (nodes && nodes[nth]) nodes[nth].textContent = value;
            };
            updateText('.card.border-left-primary .h5', 0, stats.total_students ?? '');
            updateText('.card.border-left-success .h5', 0, stats.active_students ?? '');
            updateText('.card.border-left-info .h5', 0, stats.graduated_students ?? '');
            updateText('.card.border-left-warning .h5', 0, stats.pending_files ?? '');
            updateText('.card.border-left-success .h5', 1, stats.approved_files ?? '');
            updateText('.card.border-left-danger .h5', 0, stats.missing_files ?? '');
        })
        .catch(() => {});
}, 30000);
</script>
@endpush
