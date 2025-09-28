@extends('layouts.teacher')

@section('title', 'My Subjects - EDUgate Teacher')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-book me-2"></i>My Subjects
            </h1>
            <p class="text-muted mb-0">Manage subjects and upload teaching materials</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-success" onclick="openAddSubjectModal()">
                <i class="fas fa-plus me-2"></i>Add Subject
            </button>
            <button class="btn btn-primary" onclick="refreshSubjects()">
                <i class="fas fa-sync me-2"></i>Refresh
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Subjects</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalSubjects">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Subjects</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="activeSubjects">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">With Materials</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="subjectsWithFiles">0</div>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Files</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalFiles">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subjects Grid -->
    <div class="row" id="subjectsGrid">
        <div class="col-12">
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Loading subjects...</p>
            </div>
        </div>
    </div>
</div>

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>Add New Subject
                </h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addSubjectForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="subjectName" class="form-label">Subject Name *</label>
                            <input type="text" class="form-control" id="subjectName" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="subjectCode" class="form-label">Subject Code *</label>
                            <input type="text" class="form-control" id="subjectCode" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="subjectGrade" class="form-label">Grade Level</label>
                            <select class="form-select" id="subjectGrade">
                                <option value="">All Grades</option>
                                <option value="7">Grade 7</option>
                                <option value="8">Grade 8</option>
                                <option value="9">Grade 9</option>
                                <option value="10">Grade 10</option>
                                <option value="11">Grade 11</option>
                                <option value="12">Grade 12</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="subjectCredits" class="form-label">Credits</label>
                            <input type="number" class="form-control" id="subjectCredits" min="1" max="10">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="subjectDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="subjectDescription" rows="3"></textarea>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Subject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Subject Modal -->
<div class="modal fade" id="editSubjectModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Edit Subject
                </h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="editSubjectModalBody">
                <!-- Edit form will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Upload Files Modal -->
<div class="modal fade" id="uploadFilesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-upload me-2"></i>Upload Teaching Materials
                </h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="uploadFilesModalBody">
                <!-- Upload form will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Subject Files Modal -->
<div class="modal fade" id="subjectFilesModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-file-alt me-2"></i>Subject Materials
                </h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="subjectFilesModalBody">
                <!-- Files list will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Subject card styling */
.subject-card {
    transition: all 0.3s ease;
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.subject-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.subject-card .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px 12px 0 0 !important;
    border: none;
}

.subject-card .card-body {
    padding: 1.5rem;
}

/* Subject stats */
.subject-stats {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.stat-item {
    text-align: center;
    flex: 1;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #667eea;
}

.stat-label {
    font-size: 0.75rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Action buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}

.action-buttons .btn {
    flex: 1;
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
}

/* File list styling */
.file-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    margin-bottom: 0.5rem;
    transition: all 0.2s ease;
}

.file-item:hover {
    background-color: #f8f9fa;
    border-color: #667eea;
}

.file-icon {
    width: 40px;
    height: 40px;
    background-color: #667eea;
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
}

.file-info {
    flex: 1;
}

.file-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.file-meta {
    font-size: 0.875rem;
    color: #6c757d;
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

/* Upload area */
.upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-area:hover {
    border-color: #667eea;
    background-color: #f8f9fa;
}

.upload-area.dragover {
    border-color: #667eea;
    background-color: #e3f2fd;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column;
    }
    
    .subject-stats {
        flex-direction: column;
        gap: 1rem;
    }
    
    .stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
let subjects = [];

document.addEventListener('DOMContentLoaded', function() {
    loadSubjects();
    
    document.getElementById('addSubjectForm').addEventListener('submit', function(e) {
        e.preventDefault();
        addSubject();
    });
});

function loadSubjects() {
    fetch('/teacher/subjects', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }})
        .then(r => r.json())
        .then(({ success, subjects: items }) => {
            if (!success) throw new Error('Failed to load subjects');
            // Normalize to expected shape for UI
            subjects = items.map(s => ({
                id: s.id,
                name: s.subject_name || s.name || 'Subject',
                code: s.subject_code || s.code || '-',
                grade_level: s.class || s.grade_level || '',
                credits: s.credits || 'N/A',
                description: s.description || '',
                status: s.status || 'Active',
                files_count: s.files_count || 0,
                students_count: s.students_count || 0,
                created_at: s.created_at
            }));
            updateStats();
            renderSubjects();
        })
        .catch(() => {
            document.getElementById('subjectsGrid').innerHTML = `<div class="col-12"><div class="alert alert-danger">Failed to load subjects.</div></div>`;
        });
}

function updateStats() {
    const totalSubjects = subjects.length;
    const activeSubjects = subjects.filter(s => s.status === 'Active').length;
    const subjectsWithFiles = subjects.filter(s => s.files_count > 0).length;
    const totalFiles = subjects.reduce((sum, s) => sum + s.files_count, 0);
    
    document.getElementById('totalSubjects').textContent = totalSubjects;
    document.getElementById('activeSubjects').textContent = activeSubjects;
    document.getElementById('subjectsWithFiles').textContent = subjectsWithFiles;
    document.getElementById('totalFiles').textContent = totalFiles;
}

function renderSubjects() {
    const grid = document.getElementById('subjectsGrid');
    
    if (subjects.length === 0) {
        grid.innerHTML = `
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-book fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No subjects found</h5>
                        <p class="text-muted">Start by adding your first subject.</p>
                        <button class="btn btn-primary" onclick="openAddSubjectModal()">
                            <i class="fas fa-plus me-2"></i>Add Subject
                        </button>
                    </div>
                </div>
            </div>
        `;
        return;
    }
    
    grid.innerHTML = subjects.map(subject => `
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card subject-card h-100">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-book me-2"></i>${subject.name}
                    </h6>
                    <small class="opacity-75">${subject.code}</small>
                </div>
                <div class="card-body">
                    <div class="subject-stats">
                        <div class="stat-item">
                            <div class="stat-number">${subject.files_count}</div>
                            <div class="stat-label">Files</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">${subject.students_count}</div>
                            <div class="stat-label">Students</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">${subject.credits}</div>
                            <div class="stat-label">Credits</div>
                        </div>
                    </div>
                    
                    <p class="text-muted mb-2">${subject.description}</p>
                    
                    <div class="mb-2">
                        <span class="badge bg-info">Grade ${subject.grade_level}</span>
                        <span class="badge bg-success">${subject.status}</span>
                    </div>
                    
                    <div class="action-buttons">
                        <button class="btn btn-outline-primary btn-sm" onclick="viewSubjectFiles(${subject.id})">
                            <i class="fas fa-file-alt me-1"></i>Files
                        </button>
                        <button class="btn btn-outline-success btn-sm" onclick="uploadSubjectFiles(${subject.id})">
                            <i class="fas fa-upload me-1"></i>Upload
                        </button>
                        <button class="btn btn-outline-warning btn-sm" onclick="editSubject(${subject.id})">
                            <i class="fas fa-edit me-1"></i>Edit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function openAddSubjectModal() {
    const modal = new bootstrap.Modal(document.getElementById('addSubjectModal'));
    modal.show();
}

function addSubject() {
    const form = document.getElementById('addSubjectForm');
    const payload = {
        subject_name: document.getElementById('subjectName').value,
        subject_code: document.getElementById('subjectCode').value,
        class: document.getElementById('subjectGrade').value || 'All',
        description: document.getElementById('subjectDescription').value
    };

    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating...';
    submitBtn.disabled = true;

    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch('/teacher/subjects', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify(payload)
    }).then(r => r.json()).then(resp => {
        if (!resp.success) throw new Error(resp.message || 'Failed to create subject');
        loadSubjects();
        bootstrap.Modal.getInstance(document.getElementById('addSubjectModal')).hide();
        form.reset();
        showSuccess('Subject created successfully!');
    }).catch(err => {
        alert(err.message || 'Failed to create subject');
    }).finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

function editSubject(subjectId) {
    const subject = subjects.find(s => s.id === subjectId);
    if (!subject) return;
    
    const modal = new bootstrap.Modal(document.getElementById('editSubjectModal'));
    document.getElementById('editSubjectModalBody').innerHTML = `
        <form id="editSubjectForm">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="editSubjectName" class="form-label">Subject Name *</label>
                    <input type="text" class="form-control" id="editSubjectName" value="${subject.name}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="editSubjectCode" class="form-label">Subject Code *</label>
                    <input type="text" class="form-control" id="editSubjectCode" value="${subject.code}" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="editSubjectGrade" class="form-label">Grade Level</label>
                    <select class="form-select" id="editSubjectGrade">
                        <option value="">All Grades</option>
                        <option value="7" ${subject.grade_level === '7' ? 'selected' : ''}>Grade 7</option>
                        <option value="8" ${subject.grade_level === '8' ? 'selected' : ''}>Grade 8</option>
                        <option value="9" ${subject.grade_level === '9' ? 'selected' : ''}>Grade 9</option>
                        <option value="10" ${subject.grade_level === '10' ? 'selected' : ''}>Grade 10</option>
                        <option value="11" ${subject.grade_level === '11' ? 'selected' : ''}>Grade 11</option>
                        <option value="12" ${subject.grade_level === '12' ? 'selected' : ''}>Grade 12</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="editSubjectCredits" class="form-label">Credits</label>
                    <input type="number" class="form-control" id="editSubjectCredits" value="${subject.credits}" min="1" max="10">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="editSubjectDescription" class="form-label">Description</label>
                <textarea class="form-control" id="editSubjectDescription" rows="3">${subject.description}</textarea>
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save me-2"></i>Update Subject
                </button>
            </div>
        </form>
    `;
    
    modal.show();
    
    // Add form submit handler
    document.getElementById('editSubjectForm').addEventListener('submit', function(e) {
        e.preventDefault();
        updateSubject(subjectId);
    });
}

function updateSubject(subjectId) {
    const subject = subjects.find(s => s.id === subjectId);
    if (!subject) return;

    const submitBtn = document.querySelector('#editSubjectForm button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
    submitBtn.disabled = true;

    const payload = {
        subject_name: document.getElementById('editSubjectName').value,
        subject_code: document.getElementById('editSubjectCode').value,
        class: document.getElementById('editSubjectGrade').value || 'All',
        description: document.getElementById('editSubjectDescription').value
    };
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch(`/teacher/subjects/${subjectId}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify(payload)
    }).then(r => r.json()).then(resp => {
        if (!resp.success) throw new Error(resp.message || 'Failed to update subject');
        loadSubjects();
        bootstrap.Modal.getInstance(document.getElementById('editSubjectModal')).hide();
        showSuccess('Subject updated successfully!');
    }).catch(err => {
        alert(err.message || 'Failed to update subject');
    }).finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

function uploadSubjectFiles(subjectId) {
    const subject = subjects.find(s => s.id === subjectId);
    if (!subject) return;
    
    const modal = new bootstrap.Modal(document.getElementById('uploadFilesModal'));
    document.getElementById('uploadFilesModalBody').innerHTML = `
        <div class="text-center mb-4">
            <h6>Upload Materials for ${subject.name}</h6>
            <p class="text-muted">Upload lesson plans, assignments, and other teaching materials</p>
        </div>
        
        <form id="uploadFilesForm">
            <div class="upload-area" onclick="document.getElementById('fileInput').click()">
                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                <h5>Click to upload files</h5>
                <p class="text-muted">or drag and drop files here</p>
                <small class="text-muted">Supports PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX</small>
            </div>
            <input type="file" id="fileInput" multiple accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx" style="display: none;">
            
            <div id="selectedFiles" class="mt-3"></div>
            
            <div class="d-flex justify-content-end gap-2 mt-4">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-upload me-2"></i>Upload Files
                </button>
            </div>
        </form>
    `;
    
    modal.show();
    
    // Add file input handler
    document.getElementById('fileInput').addEventListener('change', function(e) {
        displaySelectedFiles(e.target.files);
    });
    
    // Add form submit handler
    document.getElementById('uploadFilesForm').addEventListener('submit', function(e) {
        e.preventDefault();
        uploadFiles(subjectId);
    });
}

function displaySelectedFiles(files) {
    const container = document.getElementById('selectedFiles');
    
    if (files.length === 0) {
        container.innerHTML = '';
        return;
    }
    
    container.innerHTML = `
        <h6>Selected Files:</h6>
        ${Array.from(files).map(file => `
            <div class="file-item">
                <div class="file-icon">
                    <i class="fas fa-file"></i>
                </div>
                <div class="file-info">
                    <div class="file-name">${file.name}</div>
                    <div class="file-meta">${(file.size / 1024 / 1024).toFixed(2)} MB</div>
                </div>
            </div>
        `).join('')}
    `;
}

function uploadFiles(subjectId) {
    const subject = subjects.find(s => s.id === subjectId);
    if (!subject) return;
    
    const submitBtn = document.querySelector('#uploadFilesForm button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
    submitBtn.disabled = true;
    
    setTimeout(() => {
        // Simulate file upload
        const fileCount = document.getElementById('fileInput').files.length;
        subject.files_count += fileCount;
        
        updateStats();
        renderSubjects();
        
        bootstrap.Modal.getInstance(document.getElementById('uploadFilesModal')).hide();
        
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        showSuccess(`${fileCount} file(s) uploaded successfully!`);
    }, 2000);
}

function viewSubjectFiles(subjectId) {
    const subject = subjects.find(s => s.id === subjectId);
    if (!subject) return;
    
    const modal = new bootstrap.Modal(document.getElementById('subjectFilesModal'));
    document.getElementById('subjectFilesModalBody').innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6>Teaching Materials - ${subject.name}</h6>
            <button class="btn btn-success btn-sm" onclick="uploadSubjectFiles(${subject.id})">
                <i class="fas fa-plus me-1"></i>Add Files
            </button>
        </div>
        
        <div class="row">
            ${generateMockFiles(subject.id).map(file => `
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="file-item">
                        <div class="file-icon">
                            <i class="fas fa-file-${getFileIcon(file.type)}"></i>
                        </div>
                        <div class="file-info">
                            <div class="file-name">${file.name}</div>
                            <div class="file-meta">${file.size} • ${file.date}</div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i>Download</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>Preview</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            `).join('')}
        </div>
    `;
    
    modal.show();
}

function generateMockFiles(subjectId) {
    const fileTypes = ['pdf', 'doc', 'ppt', 'xls'];
    const fileNames = ['Lesson Plan', 'Assignment', 'Quiz', 'Worksheet', 'Notes', 'Presentation'];
    
    const files = [];
    for (let i = 0; i < Math.floor(Math.random() * 5) + 1; i++) {
        files.push({
            id: i + 1,
            name: `${fileNames[Math.floor(Math.random() * fileNames.length)]} ${i + 1}.${fileTypes[Math.floor(Math.random() * fileTypes.length)]}`,
            type: fileTypes[Math.floor(Math.random() * fileTypes.length)],
            size: `${(Math.random() * 5 + 1).toFixed(1)} MB`,
            date: new Date(Date.now() - Math.random() * 30 * 24 * 60 * 60 * 1000).toLocaleDateString()
        });
    }
    
    return files;
}

function getFileIcon(type) {
    switch (type) {
        case 'pdf': return 'pdf';
        case 'doc': case 'docx': return 'word';
        case 'ppt': case 'pptx': return 'powerpoint';
        case 'xls': case 'xlsx': return 'excel';
        default: return '';
    }
}

function refreshSubjects() {
    loadSubjects();
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
</script>
@endpush
