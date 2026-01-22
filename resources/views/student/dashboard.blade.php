@extends('layouts.student')

@section('title', 'Student Dashboard - EDUgate')

@section('content')
<div class="container-fluid px-4 attio-dashboard">
    <!-- Page Header -->
    <div class="page-header mb-5">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Welcome back, {{ $studentData['user']->name ?? 'Student' }}</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions mb-5">
        <button class="action-btn-primary" onclick="viewMyProfile()">
            <i class="fas fa-user"></i>
            <span>My Profile</span>
        </button>
        <button class="action-btn-primary" onclick="checkMyStatus()">
            <i class="fas fa-user-check"></i>
            <span>My Status</span>
        </button>
        <a class="action-btn-primary" href="{{ route('student.enrollment.edit') }}">
            <i class="fas fa-user-edit"></i>
            <span>Edit Enrollment Info</span>
        </a>
        <a class="action-btn-primary" href="{{ route('student.files.index') }}">
            <i class="fas fa-file-alt"></i>
            <span>My Documents</span>
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid mb-5">
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-label">My Grade</div>
                <div class="stat-value">{{ $studentData['grade'] ? 'Grade ' . $studentData['grade'] : 'Not Assigned' }}</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-layer-group"></i>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-label">Status</div>
                <div class="stat-value">{{ ucfirst($studentData['status']) }}</div>
            </div>
            <div class="stat-icon">
                @if($studentData['status'] == 'Active')
                    <i class="fas fa-check-circle"></i>
                @elseif($studentData['status'] == 'pending')
                    <i class="fas fa-clock"></i>
                @else
                    <i class="fas fa-user-check"></i>
                @endif
            </div>
        </div>
        <div class="stat-card" onclick="window.location.href='{{ route('student.files.index') }}'">
            <div class="stat-content">
                <div class="stat-label">My Documents</div>
                <div class="stat-value">{{ is_countable($documents) ? count($documents) : 0 }}</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-file-alt"></i>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-label">Classmates</div>
                <div class="stat-value">{{ is_countable($classmates) ? count($classmates) : 0 }}</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="content-grid mb-5">
        @if($studentData['student'])
        <div class="info-section">
            <div class="section-card">
                <div class="section-header">
                    <h3 class="section-title">My Information</h3>
                </div>
                <div class="section-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Full Name</div>
                            <div class="info-value">{{ $studentData['student']->first_name ?? $studentData['student']->firstname }} {{ $studentData['student']->last_name ?? $studentData['student']->lastname }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $studentData['user']->email }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Contact</div>
                            <div class="info-value">{{ $studentData['student']->contact_number ?? 'Not provided' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Student ID</div>
                            <div class="info-value">{{ $studentData['student']->id ?? 'Not assigned' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Enrollment Date</div>
                            <div class="info-value">{{ $studentData['enrollment_date'] ? $studentData['enrollment_date']->format('M d, Y') : 'Unknown' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Address</div>
                            <div class="info-value">{{ $studentData['student']->current_address ?? $studentData['student']->address ?? 'Not provided' }}</div>
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
                        <h3>No Student Record Found</h3>
                        <p>Your account doesn't have a student record associated with it yet. Please contact the administrator to set up your student profile.</p>
                        <a href="{{ route('profile.show') }}" class="action-btn-primary">
                            <i class="fas fa-user"></i>
                            <span>View Profile</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        @if($studentData['student'])
        <div class="files-section">
            <div class="section-card">
                <div class="section-header">
                    <h3 class="section-title">Recent Documents</h3>
                </div>
                <div class="section-body">
                    @if(is_countable($documents) && count($documents) > 0)
                        <div class="files-list">
                            @foreach($documents as $document)
                            <div class="file-item">
                                <div class="file-icon">
                                    <i class="fas fa-file"></i>
                                </div>
                                <div class="file-details">
                                    <div class="file-name">{{ $document->file_name }}</div>
                                    <div class="file-student">{{ $document->created_at->format('M d, Y') }}</div>
                                </div>
                                <div class="file-status">
                                    <span class="status-badge status-{{ $document->status }}">{{ ucfirst($document->status) }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-folder-open"></i>
                            <p>No documents uploaded yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Classmates Section -->
    @if(is_countable($classmates) && count($classmates) > 0)
    <div class="students-section">
        <div class="section-card">
            <div class="section-header">
                <div>
                    <h3 class="section-title">My Classmates</h3>
                    <span class="section-count">{{ is_countable($classmates) ? count($classmates) : 0 }} classmates</span>
                </div>
                @if($studentData['grade'])
                    <span class="status-badge">Grade {{ $studentData['grade'] }}</span>
                @endif
            </div>
            <div class="section-body">
                <div class="students-grid">
                    @foreach($classmates as $classmate)
                    <div class="student-card">
                        <div class="student-avatar">
                            {{ strtoupper(substr($classmate->first_name ?? $classmate->firstname ?? 'S', 0, 1)) }}
                        </div>
                        <div class="student-info">
                            <div class="student-name">
                                {{ $classmate->first_name ?? $classmate->firstname }} 
                                {{ $classmate->last_name ?? $classmate->lastname }}
                            </div>
                            <div class="student-id">ID: {{ $classmate->id }}</div>
                            @if($classmate->user && $classmate->user->email)
                            <div class="student-email">{{ $classmate->user->email }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="students-section">
        <div class="section-card">
            <div class="section-body text-center py-5">
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h3>No Classmates Found</h3>
                    <p>You don't have any classmates in your grade level yet.</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Student Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content attio-modal">
            <div class="modal-header">
                <h5 class="modal-title">My Academic Status</h5>
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

/* Quick Actions */
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
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.action-btn-primary i {
    font-size: 0.875rem;
    opacity: 0.7;
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
    box-shadow: 0 4px 12px rgba(0,0,0,0.04);
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

.status-missing {
    background: #faf5e6;
    color: #8b6914;
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
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
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
    box-shadow: 0 8px 32px rgba(0,0,0,0.08);
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

function checkMyStatus() {
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    document.getElementById('statusModalBody').innerHTML = `
        <div class="text-center py-3">
            <div class="spinner-border text-dark" role="status"></div>
            <p class="mt-2">Loading your academic status...</p>
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
                        <h5 style="margin: 0; font-size: 1rem; font-weight: 600; color: #1a1a1a;">Academic Standing: Good</h5>
                    </div>
                    <p style="margin: 0; font-size: 0.875rem; color: #666;">You are in good academic standing with no pending requirements.</p>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div style="background: #ffffff; border: 1px solid #e5e5e5; border-radius: 8px; padding: 1rem;">
                        <h6 style="margin: 0 0 1rem; font-size: 0.875rem; font-weight: 600; color: #1a1a1a; text-transform: uppercase; letter-spacing: 0.5px;">Current Enrollment</h6>
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <div>
                                <div style="font-size: 0.75rem; color: #666; margin-bottom: 0.25rem;">Grade</div>
                                <div style="font-size: 0.875rem; color: #1a1a1a; font-weight: 500;">{{ $studentData['grade'] ? 'Grade ' . $studentData['grade'] : 'Not Assigned' }}</div>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; color: #666; margin-bottom: 0.25rem;">Status</div>
                                <span style="display: inline-block; padding: 0.25rem 0.625rem; background: #f0f0f0; color: #1a1a1a; border-radius: 4px; font-size: 0.75rem; font-weight: 500;">{{ ucfirst($studentData['status']) }}</span>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; color: #666; margin-bottom: 0.25rem;">Enrollment Date</div>
                                <div style="font-size: 0.875rem; color: #1a1a1a; font-weight: 500;">{{ $studentData['enrollment_date'] ? $studentData['enrollment_date']->format('M d, Y') : 'Unknown' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="background: #ffffff; border: 1px solid #e5e5e5; border-radius: 8px; padding: 1rem;">
                        <h6 style="margin: 0 0 1rem; font-size: 0.875rem; font-weight: 600; color: #1a1a1a; text-transform: uppercase; letter-spacing: 0.5px;">Document Status</h6>
                        <div style="display: flex; flex-direction: column; gap: 0.625rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #1a1a1a;">
                                <i class="fas fa-check" style="color: #1a1a1a;"></i>
                                <span>Enrollment Form</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #1a1a1a;">
                                <i class="fas fa-check" style="color: #1a1a1a;"></i>
                                <span>Birth Certificate</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #1a1a1a;">
                                <i class="fas fa-check" style="color: #1a1a1a;"></i>
                                <span>Report Card</span>
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
