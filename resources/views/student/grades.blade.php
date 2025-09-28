@extends('layouts.student')

@section('title', 'My Grade Level - EDUgate')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-users me-2"></i>Grade {{ $grade }} Students
            </h1>
            <p class="text-muted mb-0">All enrolled and approved students in your grade level</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Stats Card -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $gradeStudents->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $gradeStudents->where('status', 'Active')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Enrolled Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $gradeStudents->where('status', 'enrolled')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Grade Level</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $grade }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-layer-group fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Students List -->
    @if($gradeStudents->count() > 0)
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Students in Grade {{ $grade }}
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($gradeStudents as $student)
                <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px; font-size: 20px;">
                                {{ strtoupper(substr($student->first_name ?? $student->firstname ?? 'S', 0, 1)) }}
                            </div>
                            <h6 class="card-title mb-1">
                                {{ $student->first_name ?? $student->firstname }} 
                                {{ $student->last_name ?? $student->lastname }}
                            </h6>
                            
                           
                            
                            <div class="mb-2">
                                @if($student->status == 'Active')
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle me-1"></i>Active
                                    </span>
                                @elseif($student->status == 'enrolled')
                                    <span class="badge badge-primary">
                                        <i class="fas fa-user-graduate me-1"></i>Enrolled
                                    </span>
                                @else
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-user me-1"></i>{{ ucfirst($student->status) }}
                                    </span>
                                @endif
                            </div>
                            
                            @if($student->user && $student->user->email)
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-envelope me-1"></i>{{ $student->user->email }}
                                </small>
                            </div>
                            @endif
                            
                            @if($student->contact_number)
                            <div class="mt-1">
                                <small class="text-muted">
                                    <i class="fas fa-phone me-1"></i>{{ $student->contact_number }}
                                </small>
                            </div>
                            @endif
                        </div>
                        <div class="card-footer bg-light text-center">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                Enrolled: {{ $student->created_at->format('M d, Y') }}
                            </small>
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
                <h5>No Students Found</h5>
                <p>There are no enrolled or approved students in Grade {{ $grade }} yet.</p>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.badge {
    font-size: 0.75em;
}

.card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease-in-out;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.text-gray-300 {
    color: #dddfeb !important;
}
</style>
@endpush
