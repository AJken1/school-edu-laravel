@extends('layouts.teacher')

@section('title', 'My Students - EDUgate Teacher')

@section('content')
<div class="container-fluid px-4 attio-dashboard">
    <!-- Page Header -->
    <div class="page-header mb-5">
        <div>
            <h1 class="page-title">My Students</h1>
            <p class="page-subtitle">Manage and view all enrolled students</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button class="action-btn-primary" onclick="exportStudents()">
                <i class="fas fa-download"></i>
                <span>Export List</span>
            </button>
            <button class="action-btn-primary" onclick="refreshStudents()">
                <i class="fas fa-sync"></i>
                <span>Refresh</span>
            </button>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="section-card mb-4">
        <div class="section-header">
            <h3 class="section-title">
                <i class="fas fa-filter"></i>
                Filters & Search
            </h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="searchInput" class="form-label attio-label">Search Students</label>
                    <input type="text" class="form-control attio-input" id="searchInput" placeholder="Search by name or email...">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="statusFilter" class="form-label attio-label">Status</label>
                    <select class="form-select attio-input" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="enrolled">Enrolled</option>
                        <option value="pending">Pending</option>
                        <option value="graduated">Graduated</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="gradeFilter" class="form-label attio-label">Grade Level</label>
                    <select class="form-select attio-input" id="gradeFilter">
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
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button class="action-btn-primary w-100" onclick="applyFilters()">
                        <i class="fas fa-search"></i>
                        <span>Filter</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid mb-5">
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-label">Total Students</div>
                <div class="stat-value" id="totalStudents">0</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-label">Active Students</div>
                <div class="stat-value" id="activeStudents">0</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-label">Pending</div>
                <div class="stat-value" id="pendingStudents">0</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-label">Graduated</div>
                <div class="stat-value" id="graduatedStudents">0</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
        </div>
    </div>

    <!-- Students Grid -->
    <div class="section-card">
        <div class="section-header">
            <div>
                <h3 class="section-title">
                    <i class="fas fa-list"></i>
                    Students List
                </h3>
                <span class="section-count" id="paginationInfo">Loading...</span>
            </div>
        </div>
        <div class="section-body">
            <div class="row" id="studentsGrid">
                <div class="col-12 text-center py-4">
                    <div class="spinner-border text-dark" role="status"></div>
                    <p class="mt-2" style="color: #666;">Loading students...</p>
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
        <div class="modal-content attio-modal">
            <div class="modal-header">
                <h5 class="modal-title">Edit Student Information</h5>
                <button type="button" class="modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
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
        <div class="modal-content attio-modal">
            <div class="modal-header">
                <h5 class="modal-title">Student Details</h5>
                <button type="button" class="modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
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
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

.attio-dashboard {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    color: #1a1a1a;
    background: #fafafa;
    min-height: 100vh;
    padding: 2rem 0;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2.5rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
    letter-spacing: -0.02em;
}

.page-subtitle {
    font-size: 0.9375rem;
    color: #666;
    margin: 0.5rem 0 0;
    font-weight: 400;
}

.action-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.125rem;
    background: #ffffff;
    border: 1px solid #e5e5e5;
    border-radius: 8px;
    color: #1a1a1a;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
    font-family: 'Inter', sans-serif;
}

.action-btn-primary:hover {
    background: #f5f5f5;
    border-color: #d0d0d0;
    color: #1a1a1a;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
}

.action-btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 0.875rem;
    background: transparent;
    border: 1px solid #e5e5e5;
    border-radius: 6px;
    color: #1a1a1a;
    font-size: 0.8125rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
}

.action-btn-secondary:hover {
    background: #f5f5f5;
    border-color: #d0d0d0;
}

.section-card {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    overflow: hidden;
}

.section-header {
    padding: 1.5rem;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.section-title {
    font-size: 1.125rem;
    font-weight: 500;
    color: #1a1a1a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    letter-spacing: -0.01em;
}

.section-count {
    font-size: 0.8125rem;
    color: #666;
    font-weight: 400;
    margin-top: 0.25rem;
    display: block;
}

.section-body {
    padding: 1.5rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1rem;
    margin-bottom: 2.5rem;
}

.stat-card {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.2s ease;
}

.stat-card:hover {
    border-color: #d0d0d0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    transform: translateY(-2px);
}

.stat-label {
    font-size: 0.8125rem;
    color: #666;
    font-weight: 400;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 2rem;
    font-weight: 600;
    color: #1a1a1a;
    line-height: 1;
    letter-spacing: -0.02em;
}

.stat-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f5f5f5;
    border-radius: 8px;
    color: #666;
    font-size: 1.25rem;
}

.attio-label {
    font-size: 0.8125rem;
    color: #666;
    font-weight: 500;
    margin-bottom: 0.5rem;
    display: block;
}

.status-badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.375rem 0.75rem;
    border-radius: 4px;
    display: inline-block;
    background: #f0f0f0;
    color: #1a1a1a;
}

.attio-input {
    border: 1px solid #e5e5e5;
    border-radius: 6px;
    padding: 0.625rem 0.875rem;
    font-size: 0.875rem;
    color: #1a1a1a;
    background: #ffffff;
    transition: all 0.2s ease;
    width: 100%;
}

.attio-input:focus {
    outline: none;
    border-color: #1a1a1a;
    box-shadow: 0 0 0 3px rgba(26, 26, 26, 0.05);
}

.section-card {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    overflow: hidden;
}

.section-header {
    padding: 1.5rem;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fafafa;
}

.section-title {
    font-size: 1.125rem;
    font-weight: 500;
    color: #1a1a1a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    letter-spacing: -0.01em;
}

.section-count {
    font-size: 0.8125rem;
    color: #666;
    font-weight: 400;
    margin-top: 0.25rem;
    display: block;
}

.section-body {
    padding: 1.5rem;
}

.student-card {
    transition: all 0.2s ease;
}

.student-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.status-badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.375rem 0.75rem;
    border-radius: 4px;
    display: inline-block;
    background: #f0f0f0;
    color: #1a1a1a;
}

.pagination .page-link {
    border-radius: 6px;
    margin: 0 2px;
    border: 1px solid #e5e5e5;
    color: #1a1a1a;
    padding: 0.5rem 0.75rem;
    text-decoration: none;
    transition: all 0.2s ease;
}

.pagination .page-link:hover {
    background: #f5f5f5;
    border-color: #d0d0d0;
}

.pagination .page-item.active .page-link {
    background-color: #1a1a1a;
    border-color: #1a1a1a;
    color: #ffffff;
}

.attio-modal .modal-content {
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
}

.attio-modal .modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid #f0f0f0;
    background: #ffffff;
}

.attio-modal .modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1a1a1a;
    letter-spacing: -0.01em;
}

.modal-close {
    background: none;
    border: none;
    color: #666;
    font-size: 1.125rem;
    cursor: pointer;
    padding: 0.25rem;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.modal-close:hover {
    background: #f5f5f5;
    color: #1a1a1a;
}

.attio-modal .modal-body {
    padding: 1.5rem;
}

@media (max-width: 768px) {
    .attio-dashboard {
        padding: 1rem 0;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
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
            <div class="student-card" style="background: #ffffff; border: 1px solid #e5e5e5; border-radius: 12px; padding: 1.5rem; text-align: center; height: 100%; display: flex; flex-direction: column;">
                <div style="background: #1a1a1a; color: #ffffff; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 20px; font-weight: 600;">${(student.first_name || student.firstname || 'S').charAt(0).toUpperCase()}</div>
                <h6 style="font-size: 1rem; font-weight: 500; color: #1a1a1a; margin-bottom: 0.75rem;">${student.first_name || student.firstname || ''} ${student.last_name || student.lastname || ''}</h6>
                <div class="mb-2"><span class="status-badge">${student.grade_level ? `Grade ${student.grade_level}` : (student.grade || 'Not Set')}</span></div>
                <div class="mb-2"><span class="status-badge">${student.status || 'Unknown'}</span></div>
                <small style="color: #666; font-size: 0.8125rem;">${student.email || student.user?.email || 'No email'}</small>
                <div style="margin-top: auto; padding-top: 1rem; display: flex; gap: 0.5rem; justify-content: center;">
                    <button class="action-btn-secondary" style="font-size: 0.75rem; padding: 0.375rem 0.75rem;" onclick="viewStudent(${student.id})"><i class="fas fa-eye"></i><span>View</span></button>
                    <button class="action-btn-secondary" style="font-size: 0.75rem; padding: 0.375rem 0.75rem;" onclick="editStudent(${student.id})"><i class="fas fa-edit"></i><span>Edit</span></button>
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
            <div class="spinner-border text-dark" role="status"></div>
            <p class="mt-2" style="color: #666;">Loading student details...</p>
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
            <div style="display: grid; gap: 1.5rem;">
                <div style="text-align: center; padding: 1rem;">
                    <div style="background: #1a1a1a; color: #ffffff; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 24px; font-weight: 600;">${(fullName || 'S').charAt(0).toUpperCase()}</div>
                    <h4 style="font-size: 1.5rem; font-weight: 600; color: #1a1a1a; margin-bottom: 0.5rem;">${fullName || 'Student'}</h4>
                    <p style="color: #666; font-size: 0.875rem;">Detailed information</p>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div style="background: #ffffff; border: 1px solid #e5e5e5; border-radius: 8px; padding: 1rem;">
                        <h6 style="font-size: 0.875rem; font-weight: 600; color: #1a1a1a; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #f0f0f0;">Personal Information</h6>
                        <div style="margin-bottom: 0.75rem;"><strong style="color: #666; font-size: 0.8125rem;">Student ID:</strong> <span style="color: #1a1a1a;">${student.id}</span></div>
                        <div style="margin-bottom: 0.75rem;"><strong style="color: #666; font-size: 0.8125rem;">Email:</strong> <span style="color: #1a1a1a;">${student.user?.email || '—'}</span></div>
                        <div><strong style="color: #666; font-size: 0.8125rem;">Contact:</strong> <span style="color: #1a1a1a;">${student.contact_number || '—'}</span></div>
                    </div>
                    <div style="background: #ffffff; border: 1px solid #e5e5e5; border-radius: 8px; padding: 1rem;">
                        <h6 style="font-size: 0.875rem; font-weight: 600; color: #1a1a1a; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #f0f0f0;">Academic Information</h6>
                        <div style="margin-bottom: 0.75rem;"><strong style="color: #666; font-size: 0.8125rem;">Grade Level:</strong> <span style="color: #1a1a1a;">${gradeLabel}</span></div>
                        <div style="margin-bottom: 0.75rem;"><strong style="color: #666; font-size: 0.8125rem;">Status:</strong> <span class="status-badge">${student.status}</span></div>
                        <div><strong style="color: #666; font-size: 0.8125rem;">Enrollment Date:</strong> <span style="color: #1a1a1a;">${student.created_at ? new Date(student.created_at).toLocaleDateString() : 'Unknown'}</span></div>
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
            <div class="spinner-border text-dark" role="status"></div>
            <p class="mt-2" style="color: #666;">Loading edit form...</p>
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
                        <label for="firstName" class="form-label attio-label">First Name</label>
                        <input type="text" class="form-control attio-input" id="firstName" value="${firstName}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastName" class="form-label attio-label">Last Name</label>
                        <input type="text" class="form-control attio-input" id="lastName" value="${lastName}">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label attio-label">Email</label>
                        <input type="email" class="form-control attio-input" id="email" value="${student.user?.email || ''}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="contact" class="form-label attio-label">Contact Number</label>
                        <input type="text" class="form-control attio-input" id="contact" value="${student.contact_number || ''}">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="gradeLevel" class="form-label attio-label">Grade Level</label>
                        <select class="form-select attio-input" id="gradeLevel">
                            ${[7,8,9,10,11,12].map(g => `<option value="${g}" ${String(student.grade_level || student.grade)===String(g)?'selected':''}>Grade ${g}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label attio-label">Status</label>
                        <select class="form-select attio-input" id="status">
                            ${['Active','enrolled','pending','graduated'].map(s => `<option value="${s}" ${student.status===s?'selected':''}>${s.charAt(0).toUpperCase()+s.slice(1)}</option>`).join('')}
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="address" class="form-label attio-label">Address</label>
                    <textarea class="form-control attio-input" id="address" rows="2">${student.current_address || ''}</textarea>
                </div>
                
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="action-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="action-btn-primary">
                        <i class="fas fa-save"></i>
                        <span>Save Changes</span>
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
