@extends('layouts.teacher')

@section('title', 'My Students - EDUgate Teacher')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-users me-2"></i>My Students
            </h1>
            <p class="text-muted mb-0">Manage and view all enrolled students</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-success" onclick="exportStudents()">
                <i class="fas fa-download me-2"></i>Export List
            </button>
            <button class="btn btn-primary" onclick="refreshStudents()">
                <i class="fas fa-sync me-2"></i>Refresh
            </button>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Filters & Search
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="searchInput" class="form-label">Search Students</label>
                    <input type="text" class="form-control" id="searchInput" placeholder="Search by name or email...">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="statusFilter" class="form-label">Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="enrolled">Enrolled</option>
                        <option value="pending">Pending</option>
                        <option value="graduated">Graduated</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="gradeFilter" class="form-label">Grade Level</label>
                    <select class="form-select" id="gradeFilter">
                        <option value="">All Grades</option>
                        <option value="1">Grade 1</option>
                        <option value="2">Grade 2</option>
                        <option value="3">Grade 3</option>
                        <option value="4">Grade 4</option>
                        <option value="5">Grade 5</option>
                        <option value="6">Grade 6</option>
                        <option value="7">Grade 7</option>
                        <option value="8">Grade 8</option>
                        <option value="9">Grade 9</option>
                        <option value="10">Grade 10</option>
                        <option value="11">Grade 11</option>
                        <option value="12">Grade 12</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end gap-2">
                    <button class="btn btn-primary w-100" onclick="applyFilters()">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalStudents">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="activeStudents">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="pendingStudents">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Graduated</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="graduatedStudents">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Grid (Student-portal style) -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Students List
            </h6>
            <div>
                <span class="badge badge-secondary" id="paginationInfo">Loading...</span>
            </div>
        </div>
        <div class="card-body">
            <div class="row" id="studentsGrid">
                <div class="col-12 text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Loading students...</p>
                </div>
            </div>

            <!-- Pagination -->
            <nav aria-label="Students pagination" class="mt-4">
                <ul class="pagination justify-content-center" id="pagination">
                    <!-- Pagination will be loaded here -->
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Edit Student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-edit me-2"></i>Edit Student Information
                </h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="editStudentModalBody">
                <!-- Edit form will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Student Details Modal -->
<div class="modal fade" id="studentDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user me-2"></i>Student Details
                </h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="studentDetailsModalBody">
                <!-- Student details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Student card styling */
.student-card {
    transition: all 0.3s ease;
}

.student-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Status badges */
.status-badge {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
}

/* Action buttons */
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 4px;
}

/* Pagination styling */
.pagination .page-link {
    border-radius: 6px;
    margin: 0 2px;
    border: 1px solid #dee2e6;
    color: #667eea;
}

.pagination .page-item.active .page-link {
    background-color: #667eea;
    border-color: #667eea;
}

/* Filter section */
.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
}

/* Border colors for stats cards */
.border-left-primary {
    border-left: 4px solid #667eea !important;
}

.border-left-success {
    border-left: 4px solid #38ef7d !important;
}

.border-left-warning {
    border-left: 4px solid #f093fb !important;
}

.border-left-info {
    border-left: 4px solid #36b9cc !important;
}

/* Loading states */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-sm {
        padding: 0.2rem 0.4rem;
        font-size: 0.7rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
let currentPage = 1;
let currentFilters = {
    search: '',
    status: '',
    grade: ''
};

document.addEventListener('DOMContentLoaded', function() {
    loadStudents();
    
    // Add event listeners for filters
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });
});

function loadStudents(page = 1) {
    currentPage = page;
    
    const params = new URLSearchParams({
        page: page,
        ...currentFilters
    });
    
    fetch(`/teacher/students?${params}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateStats(data.stats);
            updateTable(data.students);
            updatePagination(data.pagination);
        } else {
            showError('Failed to load students');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Error loading students');
    });
}

function updateStats(stats) {
    document.getElementById('totalStudents').textContent = stats.total || 0;
    document.getElementById('activeStudents').textContent = stats.active || 0;
    document.getElementById('pendingStudents').textContent = stats.pending || 0;
    document.getElementById('graduatedStudents').textContent = stats.graduated || 0;
}

function updateTable(students) {
    const grid = document.getElementById('studentsGrid');
    
    if (!students || students.length === 0) {
        grid.innerHTML = `
            <div class="col-12 text-center py-4">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No students found</h5>
                <p class="text-muted">Try adjusting your filters or check back later.</p>
            </div>
        `;
        return;
    }
    
    grid.innerHTML = students.map((student, index) => `
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 mb-4" data-student-id="${student.id}">
            <div class="card border-0 shadow-sm h-100 student-card">
                <div class="card-body text-center">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px; font-size: 20px;">${(student.first_name || student.firstname || 'S').charAt(0).toUpperCase()}</div>
                    <h6 class="card-title mb-1">${student.first_name || student.firstname || ''} ${student.last_name || student.lastname || ''}</h6>
                    <div class="mb-2"><span class="badge bg-info">${student.grade_level ? `Grade ${student.grade_level}` : (student.grade || 'Not Set')}</span></div>
                    <div class="mb-2"><span class="badge status-badge bg-${getStatusColor(student.status)}">${student.status || 'Unknown'}</span></div>
                    <small class="text-muted">${student.email || student.user?.email || 'No email'}</small>
                </div>
                <div class="card-footer bg-light text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-sm btn-outline-info" onclick="viewStudent(${student.id})"><i class="fas fa-eye me-1"></i>View</button>
                        <button class="btn btn-sm btn-outline-primary" onclick="editStudent(${student.id})"><i class="fas fa-edit me-1"></i>Edit</button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function updatePagination(pagination) {
    const paginationEl = document.getElementById('pagination');
    const infoEl = document.getElementById('paginationInfo');
    
    if (pagination) {
        infoEl.textContent = `Showing ${pagination.from || 0} to ${pagination.to || 0} of ${pagination.total || 0} students`;
        
        let paginationHTML = '';
        
        // Previous button
        if (pagination.currentPage > 1) {
            paginationHTML += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="loadStudents(${pagination.currentPage - 1})">Previous</a>
                </li>
            `;
        }
        
        // Page numbers
        const startPage = Math.max(1, pagination.currentPage - 2);
        const endPage = Math.min(pagination.lastPage, pagination.currentPage + 2);
        
        for (let i = startPage; i <= endPage; i++) {
            paginationHTML += `
                <li class="page-item ${i === pagination.currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="loadStudents(${i})">${i}</a>
                </li>
            `;
        }
        
        // Next button
        if (pagination.currentPage < pagination.lastPage) {
            paginationHTML += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="loadStudents(${pagination.currentPage + 1})">Next</a>
                </li>
            `;
        }
        
        paginationEl.innerHTML = paginationHTML;
    }
}

function getStatusColor(status) {
    switch (status) {
        case 'Active': return 'success';
        case 'enrolled': return 'primary';
        case 'pending': return 'warning';
        case 'graduated': return 'info';
        default: return 'secondary';
    }
}

function applyFilters() {
    currentFilters = {
        search: document.getElementById('searchInput').value,
        status: document.getElementById('statusFilter').value,
        grade: document.getElementById('gradeFilter').value
    };
    loadStudents(1);
}

function refreshStudents() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('gradeFilter').value = '';
    currentFilters = { search: '', status: '', grade: '' };
    loadStudents(1);
}

function viewStudent(studentId) {
    const modal = new bootstrap.Modal(document.getElementById('studentDetailsModal'));
    document.getElementById('studentDetailsModalBody').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2">Loading student details...</p>
        </div>
    `;
    modal.show();

    fetch(`/teacher/students/${studentId}`, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }})
    .then(r => r.json())
    .then(({ success, student }) => {
        if (!success) throw new Error('Failed to load');
        const fullName = `${student.first_name || student.firstname || ''} ${student.last_name || student.lastname || ''}`.trim();
        const gradeLabel = student.grade_level ? `Grade ${student.grade_level}` : (student.grade || 'Not Set');
        document.getElementById('studentDetailsModalBody').innerHTML = `
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; font-size: 24px; font-weight: bold;">${(fullName || 'S').charAt(0).toUpperCase()}</div>
                        <h4>${fullName || 'Student'}</h4>
                        <p class="text-muted">Detailed information</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white"><h6 class="mb-0">Personal Information</h6></div>
                        <div class="card-body">
                            <p><strong>Student ID:</strong> ${student.id}</p>
                            <p><strong>Email:</strong> ${student.user?.email || '—'}</p>
                            <p><strong>Contact:</strong> ${student.contact_number || '—'}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white"><h6 class="mb-0">Academic Information</h6></div>
                        <div class="card-body">
                            <p><strong>Grade Level:</strong> ${gradeLabel}</p>
                            <p><strong>Status:</strong> <span class="badge bg-${getStatusColor(student.status)}">${student.status}</span></p>
                            <p><strong>Enrollment Date:</strong> ${student.created_at ? new Date(student.created_at).toLocaleDateString() : 'Unknown'}</p>
                        </div>
                    </div>
                </div>
            </div>`;
    })
    .catch(() => {
        document.getElementById('studentDetailsModalBody').innerHTML = `<div class="alert alert-danger">Failed to load student details.</div>`;
    });
}

function editStudent(studentId) {
    const modal = new bootstrap.Modal(document.getElementById('editStudentModal'));
    document.getElementById('editStudentModalBody').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2">Loading edit form...</p>
        </div>
    `;
    modal.show();

    fetch(`/teacher/students/${studentId}`, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }})
    .then(r => r.json())
    .then(({ success, student }) => {
        if (!success) throw new Error('Failed');
        const firstName = student.first_name || student.firstname || '';
        const lastName = student.last_name || student.lastname || '';
        document.getElementById('editStudentModalBody').innerHTML = `
            <form id="editStudentForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstName" value="${firstName}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastName" value="${lastName}">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" value="${student.user?.email || ''}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="contact" class="form-label">Contact Number</label>
                        <input type="text" class="form-control" id="contact" value="${student.contact_number || ''}">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="gradeLevel" class="form-label">Grade Level</label>
                        <select class="form-select" id="gradeLevel">
                            ${[7,8,9,10,11,12].map(g => `<option value="${g}" ${String(student.grade_level || student.grade)===String(g)?'selected':''}>Grade ${g}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status">
                            ${['Active','enrolled','pending','graduated'].map(s => `<option value="${s}" ${student.status===s?'selected':''}>${s.charAt(0).toUpperCase()+s.slice(1)}</option>`).join('')}
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" rows="2">${student.current_address || ''}</textarea>
                </div>
                
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                </div>
            </form>
        `;
        
        // Add form submit handler
        document.getElementById('editStudentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveStudentChanges(studentId);
        });
    })
    .catch(() => {
        document.getElementById('editStudentModalBody').innerHTML = `<div class="alert alert-danger">Failed to load edit form.</div>`;
    });
}

function saveStudentChanges(studentId) {
    const saveBtn = document.querySelector('#editStudentForm button[type="submit"]');
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
    saveBtn.disabled = true;

    const payload = {
        first_name: (document.getElementById('firstName').value || '').trim(),
        last_name: (document.getElementById('lastName').value || '').trim(),
        email: (document.getElementById('email').value || '').trim(),
        contact_number: (document.getElementById('contact').value || '').trim(),
        grade_level: document.getElementById('gradeLevel').value,
        status: document.getElementById('status').value,
    };

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    fetch(`/teacher/students/${studentId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {})
        },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(resp => {
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
        if (!resp.success) throw new Error(resp.message || 'Failed to update');
        bootstrap.Modal.getInstance(document.getElementById('editStudentModal')).hide();
        loadStudents(currentPage);
        showSuccess('Student information updated successfully!');
    })
    .catch(err => {
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
        showError(err.message || 'Failed to save changes');
    });
}

function exportStudents() {
    showSuccess('Export feature coming soon!');
}

function showSuccess(message) {
    // Create toast notification
    const toast = document.createElement('div');
    toast.className = 'toast align-items-center text-white bg-success border-0';
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i>${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    toast.addEventListener('hidden.bs.toast', () => {
        document.body.removeChild(toast);
    });
}

function showError(message) {
    // Create toast notification
    const toast = document.createElement('div');
    toast.className = 'toast align-items-center text-white bg-danger border-0';
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-exclamation-circle me-2"></i>${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    toast.addEventListener('hidden.bs.toast', () => {
        document.body.removeChild(toast);
    });
}
</script>
@endpush
