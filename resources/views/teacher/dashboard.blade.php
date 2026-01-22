@extends('layouts.teacher')

@section('title', 'Teacher Dashboard - EDUgate')

@section('content')
<div class="container-fluid px-4 attio-dashboard">
    <!-- Page Header -->
    <div class="page-header mb-5">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Welcome back, {{ $teacherData['user']->name ?? 'Teacher' }}</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions mb-5">
        <button class="action-btn-primary" onclick="viewMyProfile()">
            <i class="fas fa-user"></i>
            <span>My Profile</span>
        </button>
        <button class="action-btn-primary" onclick="viewMyStatus()">
            <i class="fas fa-user-check"></i>
            <span>My Status</span>
        </button>
        <a class="action-btn-primary" href="{{ route('teacher.subjects.index') }}">
            <i class="fas fa-book"></i>
            <span>Manage Subjects</span>
        </a>
        <a class="action-btn-primary" href="{{ route('teacher.students.index') }}">
            <i class="fas fa-users"></i>
            <span>Manage Students</span>
        </a>
        <a class="action-btn-primary" href="{{ route('teacher.files.index') }}">
            <i class="fas fa-file-alt"></i>
            <span>Files & Documents</span>
        </a>
    </div>


    <!-- Stats Grid -->
    <div class="stats-grid mb-5">
        <div class="stat-card" onclick="window.location.href='{{ route('teacher.students.index') }}'">
            <div class="stat-content">
                <div class="stat-label">My Students</div>
                <div class="stat-value">{{ $stats['studentCount'] ?? 0 }}</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-card" onclick="window.location.href='{{ route('teacher.subjects.index') }}'">
            <div class="stat-content">
                <div class="stat-label">My Subjects</div>
                <div class="stat-value">{{ $stats['subjectCount'] ?? 0 }}</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-book"></i>
            </div>
        </div>
        <div class="stat-card" onclick="window.location.href='{{ route('teacher.files.index') }}'">
            <div class="stat-content">
                <div class="stat-label">Files & Documents</div>
                <div class="stat-value">{{ $stats['totalFiles'] ?? 0 }}</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-file-alt"></i>
            </div>
        </div>
        <div class="stat-card" onclick="viewMyStatus()">
            <div class="stat-content">
                <div class="stat-label">My Status</div>
                <div class="stat-value">Active</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="content-grid mb-5">
        @if($teacherData['teacher'])
        <div class="info-section">
            <div class="section-card">
                <div class="section-header">
                    <h3 class="section-title">My Information</h3>
                </div>
                <div class="section-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Full Name</div>
                            <div class="info-value">{{ $teacherData['teacher']->first_name ?? $teacherData['teacher']->firstname }} {{ $teacherData['teacher']->last_name ?? $teacherData['teacher']->lastname }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $teacherData['user']->email }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Contact</div>
                            <div class="info-value">{{ $teacherData['teacher']->contact_number ?? 'Not provided' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Employee ID</div>
                            <div class="info-value">{{ $teacherData['teacher']->employee_id ?? 'Not assigned' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Employment Date</div>
                            <div class="info-value">{{ $teacherData['employment_date'] ? $teacherData['employment_date']->format('M d, Y') : 'Unknown' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Department</div>
                            <div class="info-value">{{ $teacherData['department'] ?? 'Not assigned' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="info-section">
            <div class="section-card">
                <div class="section-body text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-exclamation-circle"></i>
                        <h3>No Teacher Record Found</h3>
                        <p>Your account doesn't have a teacher record associated with it yet. Please contact the administrator to set up your teacher profile.</p>
                        <a href="{{ route('profile.show') }}" class="action-btn-primary">
                            <i class="fas fa-user"></i>
                            <span>View Profile</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        @if($teacherData['teacher'])
        <div class="files-section">
            <div class="section-card">
                <div class="section-header">
                    <h3 class="section-title">Recent Student Files</h3>
                </div>
                <div class="section-body">
                    @if(is_countable($recentFiles) && count($recentFiles) > 0)
                        <div class="files-list">
                            @foreach($recentFiles as $file)
                            <div class="file-item">
                                <div class="file-icon">
                                    <i class="fas fa-file"></i>
                                </div>
                                <div class="file-details">
                                    <div class="file-name">{{ $file->file_name }}</div>
                                    <div class="file-student">{{ $file->student->first_name ?? $file->student->firstname }} {{ $file->student->last_name ?? $file->student->lastname }}</div>
                                </div>
                                <div class="file-status">
                                    <span class="status-badge status-{{ $file->status }}">{{ ucfirst($file->status) }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-folder-open"></i>
                            <p>No files uploaded yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Students Section -->
    <div class="students-section">
        <div class="section-card">
            <div class="section-header">
                <div>
                    <h3 class="section-title">My Students</h3>
                    <span class="section-count">{{ is_countable($students) ? count($students) : 0 }} students</span>
                </div>
                <a href="{{ route('teacher.students.index') }}" class="action-btn-secondary">
                    <span>View All</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="section-body">
                @if(is_countable($students) && count($students) > 0)
                    <div class="students-grid">
                        @foreach($students as $student)
                        <div class="student-card">
                            <div class="student-avatar">
                                {{ strtoupper(substr($student->first_name ?? $student->firstname ?? 'S', 0, 1)) }}
                            </div>
                            <div class="student-info">
                                <div class="student-name">
                                    {{ $student->first_name ?? $student->firstname }} 
                                    {{ $student->last_name ?? $student->lastname }}
                                </div>
                                <div class="student-id">ID: {{ $student->id }}</div>
                                @if($student->user && $student->user->email)
                                <div class="student-email">{{ $student->user->email }}</div>
                                @endif
                                <div class="student-grade">
                                    <span class="grade-badge">{{ $student->grade_level ? 'Grade ' . $student->grade_level : ($student->grade ?? 'No Grade') }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-users"></i>
                        <h3>No Students Found</h3>
                        <p>There are no active students in the system yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Teacher Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content attio-modal">
            <div class="modal-header">
                <h5 class="modal-title">My Teaching Status</h5>
                <button type="button" class="modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
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
/* Attio-inspired Minimalist Design */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

.attio-dashboard {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    color: #1a1a1a;
    background: #fafafa;
    min-height: 100vh;
    padding: 2rem 0;
}

/* Typography */
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

.section-title {
    font-size: 1.125rem;
    font-weight: 500;
    color: #1a1a1a;
    margin: 0;
    letter-spacing: -0.01em;
}

.page-header {
    margin-bottom: 2.5rem;
}

/* Quick Actions - Fixed Visibility */
.quick-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    margin-bottom: 2.5rem;
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

.action-btn-primary i {
    font-size: 0.875rem;
    opacity: 0.7;
}

.action-btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: transparent;
    border: 1px solid #e5e5e5;
    border-radius: 6px;
    color: #1a1a1a;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}

.action-btn-secondary:hover {
    background: #f5f5f5;
    border-color: #d0d0d0;
}

/* Stats Grid */
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
    cursor: pointer;
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

.stat-card:hover .stat-icon {
    background: #eaeaea;
    color: #1a1a1a;
}

/* Section Cards */
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

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
    margin-bottom: 2.5rem;
}

@media (max-width: 992px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
}

/* Info Grid */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
}

.info-label {
    font-size: 0.8125rem;
    color: #666;
    font-weight: 400;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 0.9375rem;
    color: #1a1a1a;
    font-weight: 500;
}

/* Files List */
.files-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.file-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    border-radius: 8px;
    transition: background 0.2s ease;
}

.file-item:hover {
    background: #fafafa;
}

.file-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f5f5f5;
    border-radius: 6px;
    color: #666;
    font-size: 0.875rem;
}

.file-details {
    flex: 1;
    min-width: 0;
}

.file-name {
    font-size: 0.875rem;
    color: #1a1a1a;
    font-weight: 500;
    margin-bottom: 0.25rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.file-student {
    font-size: 0.8125rem;
    color: #666;
}

.file-status {
    flex-shrink: 0;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.625rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: capitalize;
}

.status-approved {
    background: #f0f0f0;
    color: #1a1a1a;
}

.status-pending {
    background: #faf5e6;
    color: #8b6914;
}

.status-rejected {
    background: #f5f5f5;
    color: #666;
}

/* Students Grid */
.students-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1rem;
}

.student-card {
    background: #fafafa;
    border: 1px solid #e5e5e5;
    border-radius: 10px;
    padding: 1.25rem;
    text-align: center;
    transition: all 0.2s ease;
}

.student-card:hover {
    border-color: #d0d0d0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    transform: translateY(-2px);
}

.student-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #1a1a1a;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.125rem;
    margin: 0 auto 1rem;
}

.student-name {
    font-size: 0.9375rem;
    font-weight: 500;
    color: #1a1a1a;
    margin-bottom: 0.375rem;
}

.student-id {
    font-size: 0.8125rem;
    color: #666;
    margin-bottom: 0.25rem;
}

.student-email {
    font-size: 0.8125rem;
    color: #666;
    margin-bottom: 0.5rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.grade-badge {
    display: inline-block;
    padding: 0.25rem 0.625rem;
    background: #f0f0f0;
    color: #1a1a1a;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #666;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.3;
}

.empty-state h3 {
    font-size: 1.125rem;
    font-weight: 500;
    color: #1a1a1a;
    margin-bottom: 0.5rem;
}

.empty-state p {
    font-size: 0.875rem;
    color: #666;
    margin-bottom: 1.5rem;
}

/* Modal */
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

/* Students Section */
.students-section {
    margin-bottom: 2.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .attio-dashboard {
        padding: 1rem 0;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .quick-actions {
        gap: 0.5rem;
    }
    
    .action-btn-primary {
        padding: 0.5rem 0.875rem;
        font-size: 0.8125rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .content-grid {
        gap: 1rem;
    }
    
    .students-grid {
        grid-template-columns: 1fr;
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
            <div class="spinner-border text-dark" role="status"></div>
            <p class="mt-2">Loading your teaching status...</p>
        </div>
    `;
    modal.show();
    
    // Simulate loading personal status
    setTimeout(() => {
        document.getElementById('statusModalBody').innerHTML = `
            <div style="display: grid; gap: 1.5rem;">
                <div style="background: #fafafa; border: 1px solid #e5e5e5; border-radius: 8px; padding: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <i class="fas fa-check-circle" style="color: #1a1a1a;"></i>
                        <h5 style="margin: 0; font-size: 1rem; font-weight: 600; color: #1a1a1a;">Teaching Status: Active</h5>
                    </div>
                    <p style="margin: 0; font-size: 0.875rem; color: #666;">You are an active teacher with full access to student management.</p>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div style="background: #ffffff; border: 1px solid #e5e5e5; border-radius: 8px; padding: 1rem;">
                        <h6 style="margin: 0 0 1rem; font-size: 0.875rem; font-weight: 600; color: #1a1a1a; text-transform: uppercase; letter-spacing: 0.5px;">Current Assignment</h6>
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <div>
                                <div style="font-size: 0.75rem; color: #666; margin-bottom: 0.25rem;">Department</div>
                                <div style="font-size: 0.875rem; color: #1a1a1a; font-weight: 500;">{{ $teacherData['department'] ?? 'Not Assigned' }}</div>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; color: #666; margin-bottom: 0.25rem;">Employee ID</div>
                                <div style="font-size: 0.875rem; color: #1a1a1a; font-weight: 500;">{{ $teacherData['teacher']->employee_id ?? 'Not Assigned' }}</div>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; color: #666; margin-bottom: 0.25rem;">Status</div>
                                <span style="display: inline-block; padding: 0.25rem 0.625rem; background: #f0f0f0; color: #1a1a1a; border-radius: 4px; font-size: 0.75rem; font-weight: 500;">Active</span>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; color: #666; margin-bottom: 0.25rem;">Employment Date</div>
                                <div style="font-size: 0.875rem; color: #1a1a1a; font-weight: 500;">{{ $teacherData['employment_date'] ? $teacherData['employment_date']->format('M d, Y') : 'Unknown' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="background: #ffffff; border: 1px solid #e5e5e5; border-radius: 8px; padding: 1rem;">
                        <h6 style="margin: 0 0 1rem; font-size: 0.875rem; font-weight: 600; color: #1a1a1a; text-transform: uppercase; letter-spacing: 0.5px;">Access Permissions</h6>
                        <div style="display: flex; flex-direction: column; gap: 0.625rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #1a1a1a;">
                                <i class="fas fa-check" style="color: #1a1a1a;"></i>
                                <span>View All Students</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #1a1a1a;">
                                <i class="fas fa-check" style="color: #1a1a1a;"></i>
                                <span>Edit Student Information</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #1a1a1a;">
                                <i class="fas fa-check" style="color: #1a1a1a;"></i>
                                <span>Manage Subjects</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #1a1a1a;">
                                <i class="fas fa-check" style="color: #1a1a1a;"></i>
                                <span>Upload Files</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #1a1a1a;">
                                <i class="fas fa-check" style="color: #1a1a1a;"></i>
                                <span>View Student Documents</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div style="background: #ffffff; border: 1px solid #e5e5e5; border-radius: 8px; padding: 1rem;">
                    <h6 style="margin: 0 0 1rem; font-size: 0.875rem; font-weight: 600; color: #1a1a1a; text-transform: uppercase; letter-spacing: 0.5px;">Quick Actions</h6>
                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <a href="{{ route('teacher.students.index') }}" class="action-btn-primary">
                            <i class="fas fa-users"></i>
                            <span>Manage Students</span>
                        </a>
                        <a href="{{ route('teacher.subjects.index') }}" class="action-btn-primary">
                            <i class="fas fa-book"></i>
                            <span>Manage Subjects</span>
                        </a>
                        <a href="{{ route('teacher.files.index') }}" class="action-btn-primary">
                            <i class="fas fa-file-alt"></i>
                            <span>View Files</span>
                        </a>
                    </div>
                </div>
            </div>
        `;
    }, 1000);
}
</script>
@endpush

