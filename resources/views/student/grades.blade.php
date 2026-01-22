@extends('layouts.student')

@section('title', 'My Grade Level - EDUgate')

@section('content')
<div class="container-fluid px-4 attio-dashboard">
    <!-- Page Header -->
    <div class="page-header mb-5">
        <div>
            <h1 class="page-title">Grade {{ $grade }} Students</h1>
            <p class="page-subtitle">All enrolled and approved students in your grade level</p>
        </div>
        <a href="{{ route('student.dashboard') }}" class="action-btn-primary">
            <i class="fas fa-arrow-left"></i>
            <span>Back To Dashboard</span>
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid mb-5">
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-label">Total Students</div>
                <div class="stat-value">{{ $gradeStudents->count() }}</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-label">Active Students</div>
                <div class="stat-value">{{ $gradeStudents->where('status', 'Active')->count() }}</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-label">Enrolled Students</div>
                <div class="stat-value">{{ $gradeStudents->where('status', 'enrolled')->count() }}</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-label">Grade Level</div>
                <div class="stat-value">{{ $grade }}</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-layer-group"></i>
            </div>
        </div>
    </div>

    <!-- Students List -->
    @if($gradeStudents->count() > 0)
    <div class="section-card">
        <div class="section-header">
            <h3 class="section-title">Students in Grade {{ $grade }}</h3>
        </div>
        <div class="section-body">
            <div class="students-grid">
                @foreach($gradeStudents as $student)
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
                        @if($student->contact_number)
                        <div class="student-contact">{{ $student->contact_number }}</div>
                        @endif
                        <div class="student-status">
                            <span class="status-badge status-{{ strtolower($student->status) }}">{{ ucfirst($student->status) }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <div class="section-card">
        <div class="section-body text-center py-5">
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <h3>No Students Found</h3>
                <p>There are no enrolled or approved students in Grade {{ $grade }} yet.</p>
            </div>
        </div>
    </div>
    @endif
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
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.action-btn-primary i {
    font-size: 0.875rem;
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

.section-title {
    font-size: 1.125rem;
    font-weight: 500;
    color: #1a1a1a;
    margin: 0;
    letter-spacing: -0.01em;
}

.section-body {
    padding: 1.5rem;
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
    margin-bottom: 0.25rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.student-contact {
    font-size: 0.8125rem;
    color: #666;
    margin-bottom: 0.5rem;
}

.student-status {
    margin-top: 0.5rem;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.625rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: capitalize;
}

.status-active {
    background: #f0f0f0;
    color: #1a1a1a;
}

.status-enrolled {
    background: #f0f0f0;
    color: #1a1a1a;
}

.status-pending {
    background: #faf5e6;
    color: #8b6914;
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

/* Responsive */
@media (max-width: 768px) {
    .attio-dashboard {
        padding: 1rem 0;
    }
    
    .page-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .students-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush
