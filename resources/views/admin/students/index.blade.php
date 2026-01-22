@extends('layouts.admin')

@section('title', 'Students Management - EDUgate Admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center py-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Students Management</h1>
            <p class="text-muted mb-0">Manage student records and enrollment information</p>
        </div>
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Student
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: rgba(0, 0, 0, 1);">Total Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $students->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: rgba(0, 0, 0, 1);">Active Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $students->where('status', 'Active')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: rgba(0, 0, 0, 1);">Enrolled</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $students->where('status', 'enrolled')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: rgba(0, 0, 0, 1);">Applications</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $students->where('status', 'submitted')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
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
            <form method="GET" action="{{ route('admin.students.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search students..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="enrolled" {{ request('status') == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                            <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Applications</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="grade" class="form-control">
                            <option value="">All Grades</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('grade') == $i ? 'selected' : '' }}>Grade {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Students List</h6>
            <div>
                <button class="btn btn-success btn-sm" onclick="exportStudents()">
                    <i class="fas fa-download me-1"></i>Export
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="studentsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Grade</th>
                            <th>Status</th>
                            <th>Date Enrolled</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr data-student-id="{{ $student->id }}">
                                <td class="text-center">{{ $students->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                {{ strtoupper(substr(($student->first_name ?: $student->firstname), 0, 1)) }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">
                                                {{ ($student->first_name && $student->last_name) ? $student->first_name . ' ' . $student->last_name : ($student->firstname . ' ' . $student->lastname) }}
                                            </div>
                                            @if($student->parent_name)
                                                <small class="text-muted">Parent: {{ $student->parent_name }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($student->grade_level)
                                        Grade {{ $student->grade_level }}
                                    @elseif($student->grade)
                                        {{ $student->grade }}
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td>
                                    <select class="form-control form-control-sm" onchange="updateStudentStatus({{ $student->id }}, this.value)">
                                        @php($options = ['Active' => 'Active', 'enrolled' => 'Enrolled', 'submitted' => 'Application', 'pending' => 'Pending', 'inactive' => 'Inactive', 'graduated' => 'Graduated', 'missing_docs' => 'Missing Docs'])
                                        @foreach($options as $value => $label)
                                            <option value="{{ $value }}" {{ $student->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>{{ $student->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.students.show', $student) }}" class="btn btn-info btn-sm" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" 
                                                onclick="deleteStudent({{ $student->id }}, '{{ ($student->first_name && $student->last_name) ? $student->first_name . ' ' . $student->last_name : ($student->firstname . ' ' . $student->lastname) }}')" 
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-users fa-3x mb-3"></i>
                                        <h5>No students found</h5>
                                        <p>Start by adding your first student or check your search filters.</p>
                                        <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Add First Student
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

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="deleteModalBody">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Warning!</strong> This action cannot be undone.
                </div>
                <p>Are you sure you want to delete this student?</p>
                <p class="text-muted">This will permanently remove:</p>
                <ul class="text-muted">
                    <li>Student profile and academic records</li>
                    <li>Associated user account and login credentials</li>
                    <li>All enrollment and status history</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let deleteStudentId = null;
let deleteStudentName = null;

function deleteStudent(id, name = null) {
    deleteStudentId = id;
    deleteStudentName = name;
    
    // Update modal content with student name if provided
    if (name) {
        document.getElementById('deleteModalBody').innerHTML = `
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Warning!</strong> This action cannot be undone.
            </div>
            <p>Are you sure you want to delete <strong>${name}</strong>?</p>
            <p class="text-muted">This will permanently remove:</p>
            <ul class="text-muted">
                <li>Student profile and academic records</li>
                <li>Associated user account and login credentials</li>
                <li>All enrollment and status history</li>
            </ul>
        `;
    }
    
    // Use Bootstrap 5 syntax
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

async function confirmDelete() {
    if (!deleteStudentId) return;
    
    const deleteBtn = document.querySelector('#deleteModal .btn-danger');
    const originalText = deleteBtn.innerHTML;
    
    try {
        // Show loading state
        deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
        deleteBtn.disabled = true;
        
        const response = await fetch(`/admin/students/${deleteStudentId}`, {
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
            
            // Show success message
            showAlert('success', result.message);
            
            // Remove the student row from the table with animation
            const studentRow = document.querySelector(`tr[data-student-id="${deleteStudentId}"]`);
            if (studentRow) {
                studentRow.style.transition = 'opacity 0.3s ease-out';
                studentRow.style.opacity = '0';
                setTimeout(() => {
                    studentRow.remove();
                    updateStudentCount();
                }, 300);
            }
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
        deleteStudentId = null;
        deleteStudentName = null;
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

function updateStudentCount() {
    const rows = document.querySelectorAll('tbody tr[data-student-id]');
    const count = rows.length;
    
    // Update any count displays if they exist
    const countElements = document.querySelectorAll('.students-count');
    countElements.forEach(el => {
        el.textContent = count;
    });
    
    // Show empty state if no students left
    if (count === 0) {
        const tbody = document.querySelector('tbody');
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-4">
                    <div class="text-muted">
                        <i class="fas fa-users fa-3x mb-3"></i>
                        <h5>No students found</h5>
                        <p>Start by adding your first student or check your search filters.</p>
                        <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add First Student
                        </a>
                    </div>
                </td>
            </tr>
        `;
    }
}

function exportStudents() {
    window.location.href = "{{ route('admin.students.index') }}?export=csv";
}

async function updateStudentStatus(id, status) {
    try {
        const response = await fetch(`/admin/students/${id}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status })
        });
        const data = await response.json();
        if (!data.success) {
            alert(data.message || 'Failed to update status');
        }
    } catch (e) {
        alert('Network error while updating status');
        console.error(e);
    }
}
</script>
@endpush
