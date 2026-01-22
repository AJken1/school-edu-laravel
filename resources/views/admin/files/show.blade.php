@extends('layouts.admin')

@section('title', 'Student Files - ' . $student->firstname . ' ' . $student->lastname . ' - EDUgate Admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap">
            <div class="header-content">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.files.index') }}" class="text-decoration-none">Files Management</a></li>
                        <li class="breadcrumb-item active">{{ $student->firstname }} {{ $student->lastname }}</li>
                    </ol>
                </nav>
                <h1 class="page-title mb-2">Student Files Management</h1>
                <p class="page-subtitle">Manage enrollment documents for {{ $student->firstname }} {{ $student->lastname }}</p>
            </div>
            <div class="header-actions d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.students.show', $student) }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-user me-2"></i>View Student Profile
                </a>
                <a href="{{ route('admin.files.index') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Back to Files
                </a>
            </div>
        </div>
    </div>

    <!-- Student Info Card -->
    <div class="card student-info-card shadow-lg mb-4">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-user-graduate me-2"></i>Student Information
                </h5>
                <div class="status-section">
                    @switch($student->status)
                        @case('Active')
                            <span class="badge bg-success fs-6 px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i>Active Student
                            </span>
                            @break
                        @case('enrolled')
                            <span class="badge bg-primary fs-6 px-3 py-2">
                                <i class="fas fa-user-graduate me-1"></i>Enrolled
                            </span>
                            @break
                        @case('graduated')
                            <span class="badge bg-info fs-6 px-3 py-2">
                                <i class="fas fa-graduation-cap me-1"></i>Graduated
                            </span>
                            @break
                        @case('inactive')
                            <span class="badge bg-secondary fs-6 px-3 py-2">
                                <i class="fas fa-pause-circle me-1"></i>Inactive
                            </span>
                            @break
                        @default
                            <span class="badge bg-warning fs-6 px-3 py-2">
                                <i class="fas fa-clock me-1"></i>{{ ucfirst($student->status) }}
                            </span>
                    @endswitch
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-group">
                                <h6 class="info-label">
                                    <i class="fas fa-user me-2 text-primary"></i>Full Name
                                </h6>
                                <p class="info-value">{{ $student->firstname }} {{ $student->mi }} {{ $student->lastname }}</p>
                            </div>
                            
                            @if($student->application_id)
                            <div class="info-group">
                                <h6 class="info-label">
                                    <i class="fas fa-id-card me-2 text-primary"></i>Application ID
                                </h6>
                                <p class="info-value">{{ $student->application_id }}</p>
                            </div>
                            @endif
                            
                            @if($student->lrn_number)
                            <div class="info-group">
                                <h6 class="info-label">
                                    <i class="fas fa-hashtag me-2 text-primary"></i>LRN
                                </h6>
                                <p class="info-value">{{ $student->lrn_number }}</p>
                            </div>
                            @endif
                            
                            @if($student->grade_level || $student->grade)
                            <div class="info-group">
                                <h6 class="info-label">
                                    <i class="fas fa-graduation-cap me-2 text-primary"></i>Grade Level
                                </h6>
                                <p class="info-value">{{ $student->grade_level ?: $student->grade }}</p>
                            </div>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            @if($student->email)
                            <div class="info-group">
                                <h6 class="info-label">
                                    <i class="fas fa-envelope me-2 text-primary"></i>Email Address
                                </h6>
                                <p class="info-value">{{ $student->email }}</p>
                            </div>
                            @endif
                            
                            @if($student->phone || $student->contact_number)
                            <div class="info-group">
                                <h6 class="info-label">
                                    <i class="fas fa-phone me-2 text-primary"></i>Phone Number
                                </h6>
                                <p class="info-value">{{ $student->phone ?: $student->contact_number }}</p>
                            </div>
                            @endif
                            
                            <div class="info-group">
                                <h6 class="info-label">
                                    <i class="fas fa-calendar-alt me-2 text-primary"></i>Enrollment Date
                                </h6>
                                <p class="info-value">{{ $student->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="status-actions d-flex flex-column h-100 justify-content-center">
                        <div class="text-center mb-3">
                            <div class="status-badge-large mb-3">
                                @switch($student->status)
                                    @case('Active')
                                        <div class="status-icon bg-success">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <h6 class="status-text text-success">Active Student</h6>
                                        @break
                                    @case('enrolled')
                                        <div class="status-icon bg-primary">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                        <h6 class="status-text text-primary">Enrolled</h6>
                                        @break
                                    @case('graduated')
                                        <div class="status-icon bg-info">
                                            <i class="fas fa-graduation-cap"></i>
                                        </div>
                                        <h6 class="status-text text-info">Graduated</h6>
                                        @break
                                    @case('inactive')
                                        <div class="status-icon bg-secondary">
                                            <i class="fas fa-pause-circle"></i>
                                        </div>
                                        <h6 class="status-text text-secondary">Inactive</h6>
                                        @break
                                    @default
                                        <div class="status-icon bg-warning">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <h6 class="status-text text-warning">{{ ucfirst($student->status) }}</h6>
                                @endswitch
                            </div>
                        </div>
                        <button class="btn btn-outline-primary btn-lg" onclick="updateStudentStatus({{ $student->id }}, '{{ $student->status }}')">
                            <i class="fas fa-edit me-2"></i>Change Status
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Files Overview -->
    <div class="row mb-4">
        @php
            $totalRequired = count($fileStatus);
            $approved = collect($fileStatus)->where('file.status', 'approved')->count();
            $pending = collect($fileStatus)->where('file.status', 'pending')->count();
            $rejected = collect($fileStatus)->where('file.status', 'rejected')->count();
            $missing = collect($fileStatus)
                ->filter(function($i){ return !$i['file'] || ($i['file'] && $i['file']->status === 'missing'); })
                ->count();
            $completionPercentage = $totalRequired > 0 ? round(($approved / $totalRequired) * 100, 1) : 0;
        @endphp
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Approved Files</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $approved }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Review</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pending }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Missing Files</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $missing }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Completion</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completionPercentage }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-pie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Files List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Enrollment Documents</h6>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($fileStatus as $type => $info)
                    <div class="col-lg-6 mb-4">
                        <div class="card border-left-{{ $info['file'] ? ($info['file']->status == 'approved' ? 'success' : ($info['file']->status == 'pending' ? 'warning' : 'danger')) : 'secondary' }} h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="font-weight-bold text-gray-800 mb-1">{{ $info['name'] }}</h6>
                                        @if($info['file'])
                                            <div class="mb-2">
                                                @switch($info['file']->status)
                                                    @case('approved')
                                                        <span class="badge badge-success">
                                                            <i class="fas fa-check-circle me-1"></i>Approved
                                                        </span>
                                                        @break
                                                    @case('pending')
                                                        <span class="badge badge-warning">
                                                            <i class="fas fa-clock me-1"></i>Pending Review
                                                        </span>
                                                        @break
                                                    @case('rejected')
                                                        <span class="badge badge-danger">
                                                            <i class="fas fa-times-circle me-1"></i>Rejected
                                                        </span>
                                                        @break
                                                    @case('missing')
                                                        <span class="badge badge-secondary">
                                                            <i class="fas fa-question-circle me-1"></i>Marked Missing
                                                        </span>
                                                        @break
                                                @endswitch
                                            </div>
                                        @else
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-exclamation-triangle me-1"></i>Not Uploaded
                                            </span>
                                        @endif
                                    </div>
                                    @if($info['file'])
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('admin.files.view', $info['file']) }}" target="_blank">
                                                    <i class="fas fa-eye me-1"></i>View File
                                                </a>
                                                <a class="dropdown-item" href="{{ route('admin.files.download', $info['file']) }}">
                                                    <i class="fas fa-download me-1"></i>Download
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#" onclick="updateFileStatus({{ $info['file']->id }}, '{{ $info['file']->status }}')">
                                                    <i class="fas fa-edit me-1"></i>Update Status
                                                </a>
                                                <a class="dropdown-item text-danger" href="#" onclick="deleteFile({{ $info['file']->id }})">
                                                    <i class="fas fa-trash me-1"></i>Delete File
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                @if($info['file'])
                                    <div class="text-sm text-muted">
                                        <div><strong>File:</strong> {{ $info['file']->file_name }}</div>
                                        <div><strong>Size:</strong> {{ $info['file']->file_size_human }}</div>
                                        <div><strong>Uploaded:</strong> {{ $info['file']->uploaded_at ? $info['file']->uploaded_at->format('M d, Y H:i') : 'Unknown' }}</div>
                                        @if($info['file']->reviewed_at)
                                            <div><strong>Reviewed:</strong> {{ $info['file']->reviewed_at->format('M d, Y H:i') }}</div>
                                            @if($info['file']->reviewedBy)
                                                <div><strong>By:</strong> {{ $info['file']->reviewedBy->name }}</div>
                                            @endif
                                        @endif
                                        @if($info['file']->notes)
                                            <div class="mt-2">
                                                <strong>Notes:</strong>
                                                <div class="bg-light p-2 rounded text-sm">{{ $info['file']->notes }}</div>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-muted text-center py-3">
                                        <i class="fas fa-upload fa-2x mb-2"></i>
                                        <div>No file uploaded yet</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- File Status Update Modal -->
<div class="modal fade" id="fileStatusModal" tabindex="-1" aria-labelledby="fileStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fileStatusModalLabel">Update File Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="fileStatusForm">
                    <div class="mb-3">
                        <label for="fileStatusSelect" class="form-label">File Status</label>
                        <select name="status" id="fileStatusSelect" class="form-select">
                            <option value="pending">Pending Review</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="missing">Mark as Missing</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fileNotes" class="form-label">Notes (Optional)</label>
                        <textarea name="notes" id="fileNotes" class="form-control" rows="3" placeholder="Add any notes about this file..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmFileStatusUpdate()">Update Status</button>
            </div>
        </div>
    </div>
</div>

<!-- Student Status Update Modal -->
<div class="modal fade" id="studentStatusModal" tabindex="-1" aria-labelledby="studentStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentStatusModalLabel">Update Student Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="studentStatusForm">
                    <div class="mb-3">
                        <label for="studentStatusSelect" class="form-label">Student Status</label>
                        <select name="status" id="studentStatusSelect" class="form-select">
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmStudentStatusUpdate()">Update Status</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete File Modal -->
<div class="modal fade" id="deleteFileModal" tabindex="-1" aria-labelledby="deleteFileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteFileModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this file? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmFileDelete()">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* CRITICAL FIX: Ensure modals display above all content */
/* Bootstrap 5.3.2 modal z-index fix */

/* Reset any conflicting z-index from parent elements */
.content {
    position: relative;
    z-index: auto !important;
}

/* Backdrop should be below modal */
.modal-backdrop {
    z-index: 1055 !important;
    opacity: 0.5 !important;
}

/* Modal container */
.modal {
    z-index: 1056 !important;
    display: none;
}

.modal.show {
    display: block !important;
}

/* Modal dialog - ensure it's above backdrop */
.modal-dialog {
    position: relative;
    z-index: 1;
}

/* Modal content - the actual white box */
.modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 100%;
    pointer-events: auto;
    background-color: #fff !important;
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 0.3rem;
    outline: 0;
}

/* Ensure all interactive elements work */
.modal-content * {
    pointer-events: auto !important;
}

/* File status badges */
.badge {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

.file-card {
    transition: all 0.3s ease;
}

.file-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Dark mode support */
body.dark .modal-content {
    background-color: #2d3748 !important;
    color: #fff !important;
    border-color: #4a5568;
}

body.dark .modal-header,
body.dark .modal-body,
body.dark .modal-footer {
    background-color: #2d3748 !important;
    color: #fff !important;
    border-color: #4a5568 !important;
}

body.dark .form-control,
body.dark .form-select {
    background-color: #374151 !important;
    color: #fff !important;
    border-color: #4b5563 !important;
}
</style>
@endpush

@push('scripts')
<script>
let currentFileId = null;
let currentStudentId = {{ $student->id }};

// Initialize Bootstrap 5 modals
let fileStatusModal = null;
let studentStatusModal = null;
let deleteFileModal = null;

document.addEventListener('DOMContentLoaded', function() {
    console.log('=== Modal Initialization Debug ===');
    
    // Check if Bootstrap is loaded
    if (typeof bootstrap === 'undefined') {
        console.error('❌ Bootstrap 5 is NOT loaded!');
        alert('ERROR: Bootstrap is not loaded. Please refresh the page.');
        return;
    }
    console.log('✓ Bootstrap 5 loaded successfully');
    
    // Initialize Bootstrap 5 modals
    const fileStatusModalElement = document.getElementById('fileStatusModal');
    const studentStatusModalElement = document.getElementById('studentStatusModal');
    const deleteFileModalElement = document.getElementById('deleteFileModal');
    
    console.log('Modal elements found:', {
        fileStatus: !!fileStatusModalElement,
        studentStatus: !!studentStatusModalElement,
        deleteFile: !!deleteFileModalElement
    });
    
    if (fileStatusModalElement) {
        try {
            fileStatusModal = new bootstrap.Modal(fileStatusModalElement, {
                backdrop: true,
                keyboard: true,
                focus: true
            });
            console.log('✓ File Status Modal initialized');
        } catch (error) {
            console.error('❌ Error initializing File Status Modal:', error);
        }
    }
    
    if (studentStatusModalElement) {
        try {
            studentStatusModal = new bootstrap.Modal(studentStatusModalElement, {
                backdrop: true,
                keyboard: true,
                focus: true
            });
            console.log('✓ Student Status Modal initialized');
        } catch (error) {
            console.error('❌ Error initializing Student Status Modal:', error);
        }
    }
    
    if (deleteFileModalElement) {
        try {
            deleteFileModal = new bootstrap.Modal(deleteFileModalElement, {
                backdrop: true,
                keyboard: true,
                focus: true
            });
            console.log('✓ Delete File Modal initialized');
        } catch (error) {
            console.error('❌ Error initializing Delete File Modal:', error);
        }
    }
    
    console.log('=== Modal Initialization Complete ===');
});

// Notification function for better user feedback
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    // z-index: 1060 ensures notifications appear above modals (which use 1040-1052)
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 1060; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

function updateFileStatus(fileId, currentStatus) {
    console.log('=== updateFileStatus called ===');
    console.log('File ID:', fileId);
    console.log('Current Status:', currentStatus);
    
    currentFileId = fileId;
    const statusSelect = document.getElementById('fileStatusSelect');
    const notesTextarea = document.getElementById('fileNotes');
    
    console.log('Form elements:', {
        statusSelect: !!statusSelect,
        notesTextarea: !!notesTextarea
    });
    
    if (statusSelect) {
        statusSelect.value = currentStatus || 'pending';
        console.log('Status select value set to:', statusSelect.value);
    }
    if (notesTextarea) {
        notesTextarea.value = '';
    }
    
    if (fileStatusModal) {
        console.log('Opening modal using initialized instance...');
        try {
            fileStatusModal.show();
            console.log('✓ Modal.show() called successfully');
        } catch (error) {
            console.error('❌ Error calling modal.show():', error);
        }
    } else {
        console.warn('⚠ Modal not initialized, trying fallback...');
        // Fallback: try to show modal directly if initialization failed
        const modalElement = document.getElementById('fileStatusModal');
        if (modalElement) {
            try {
                const bsModal = new bootstrap.Modal(modalElement, {
                    backdrop: true,
                    keyboard: true,
                    focus: true
                });
                fileStatusModal = bsModal; // Store for future use
                bsModal.show();
                console.log('✓ Fallback modal created and shown');
            } catch (error) {
                console.error('❌ Fallback failed:', error);
                alert('Error: Could not open modal. Please check console for details.');
            }
        } else {
            console.error('❌ Modal element not found in DOM');
            alert('Error: Modal element not found');
        }
    }
}

function confirmFileStatusUpdate() {
    if (currentFileId) {
        const status = document.getElementById('fileStatusSelect').value;
        const notes = document.getElementById('fileNotes').value;
        const submitBtn = document.querySelector('#fileStatusModal .btn-primary');
        
        // Show loading state
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
        submitBtn.disabled = true;
        
        fetch(`/admin/files/file/${currentFileId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status, notes: notes })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (fileStatusModal) {
                    fileStatusModal.hide();
                }
                // Show success message
                showNotification('File status updated successfully!', 'success');
                location.reload();
            } else {
                showNotification('Error updating file status: ' + (data.message || 'Please try again.'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Network error. Please check your connection and try again.', 'error');
        })
        .finally(() => {
            // Restore button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    }
}

function updateStudentStatus(studentId, currentStatus) {
    currentStudentId = studentId;
    const statusSelect = document.getElementById('studentStatusSelect');
    
    if (statusSelect) {
        statusSelect.value = currentStatus;
    }
    
    if (studentStatusModal) {
        studentStatusModal.show();
    } else {
        // Fallback: try to show modal directly if initialization failed
        const modalElement = document.getElementById('studentStatusModal');
        if (modalElement) {
            const bsModal = new bootstrap.Modal(modalElement, {
                backdrop: true,
                keyboard: true,
                focus: true
            });
            studentStatusModal = bsModal; // Store for future use
            bsModal.show();
        }
    }
}

function confirmStudentStatusUpdate() {
    const status = document.getElementById('studentStatusSelect').value;
    
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
                if (studentStatusModal) {
                    studentStatusModal.hide();
                }
                showNotification('Student status updated successfully!', 'success');
                location.reload();
            } else {
                showNotification('Error updating student status: ' + (data.message || 'Please try again.'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Network error. Please check your connection and try again.', 'error');
        });
}

function deleteFile(fileId) {
    currentFileId = fileId;
    
    if (deleteFileModal) {
        deleteFileModal.show();
    } else {
        // Fallback: try to show modal directly if initialization failed
        const modalElement = document.getElementById('deleteFileModal');
        if (modalElement) {
            const bsModal = new bootstrap.Modal(modalElement, {
                backdrop: true,
                keyboard: true,
                focus: true
            });
            deleteFileModal = bsModal; // Store for future use
            bsModal.show();
        }
    }
}

function confirmFileDelete() {
    if (currentFileId) {
        fetch(`/admin/files/file/${currentFileId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (deleteFileModal) {
                    deleteFileModal.hide();
                }
                showNotification('File deleted successfully!', 'success');
                location.reload();
            } else {
                showNotification('Error deleting file: ' + (data.message || 'Please try again.'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Network error. Please check your connection and try again.', 'error');
        });
    }
}
</script>
@endpush
