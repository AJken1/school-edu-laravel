@extends('layouts.student')

@section('title', 'My Documents - EDUgate')

@section('content')
<div class="container-fluid px-4 attio-dashboard">
    <!-- Page Header -->
    <div class="page-header mb-5">
        <div>
            <h1 class="page-title">My Required Documents</h1>
            <p class="page-subtitle">Upload or replace missing/incorrect files.</p>
        </div>
        <a href="{{ route('student.dashboard') }}" class="action-btn-primary">
            <i class="fas fa-arrow-left"></i>
            <span>Back To Dashboard</span>
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-secondary mb-4">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger mb-4">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Document Cards Grid -->
    <div class="row">
        @foreach($requiredFiles as $type => $label)
            @php($file = $existingFiles->get($type))
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="section-card">
                    <div class="section-header">
                        <h3 class="section-title">{{ $label }}</h3>
                        @if($file)
                            <span class="status-badge status-{{ $file->status }}">{{ ucfirst($file->status) }}</span>
                        @else
                            <span class="status-badge status-missing">Missing</span>
                        @endif
                    </div>
                    <div class="section-body">
                        @if($file)
                            <div class="file-current mb-3">
                                <div class="file-current-label">Current File:</div>
                                <a href="{{ route('student.files.view', $file) }}" class="file-current-link" target="_blank">
                                    {{ $file->file_name }}
                                </a>
                                <div class="file-current-meta">
                                    {{ $file->file_size_human }} • {{ $file->uploaded_at?->format('M d, Y') }}
                                </div>
                                <div class="file-actions mt-2">
                                    <a href="{{ route('student.files.view', $file) }}" class="action-btn-secondary btn-sm" target="_blank">
                                        <i class="fas fa-eye"></i>
                                        <span>View</span>
                                    </a>
                                    <form method="POST" action="{{ route('student.files.destroy', $file) }}" class="d-inline" onsubmit="return confirm('Delete this file?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn-secondary btn-sm btn-danger-outline">
                                            <i class="fas fa-trash"></i>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ $file ? route('student.files.update', $file) : route('student.files.store') }}" enctype="multipart/form-data" class="file-upload-form">
                            @csrf
                            @if($file)
                                @method('PATCH')
                            @else
                                <input type="hidden" name="file_type" value="{{ $type }}">
                            @endif
                            <div class="mb-3">
                                <input type="file" name="file" class="form-control attio-input" accept="application/pdf,image/*" required>
                            </div>
                            <button type="submit" class="action-btn-primary w-100">
                                <i class="fas {{ $file ? 'fa-sync' : 'fa-upload' }}"></i>
                                <span>{{ $file ? 'Replace' : 'Upload' }}</span>
                            </button>
                        </form>
                        <div class="file-type-info">PDF/JPG/PNG, max 5MB</div>
                        @if($file && $file->notes)
                            <div class="alert alert-warning mt-3 mb-0">
                                <i class="fas fa-info-circle me-1"></i>{{ $file->notes }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
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
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.action-btn-primary i {
    font-size: 0.875rem;
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
    font-family: 'Inter', sans-serif;
}

.action-btn-secondary:hover {
    background: #f5f5f5;
    border-color: #d0d0d0;
}

.action-btn-secondary.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
}

.action-btn-secondary.btn-danger-outline {
    border-color: #dc3545;
    color: #dc3545;
}

.action-btn-secondary.btn-danger-outline:hover {
    background: #dc3545;
    color: #ffffff;
}

.section-card {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.section-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #f0f0f0;
    background: #fafafa;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.section-title {
    font-size: 1rem;
    font-weight: 500;
    color: #1a1a1a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-body {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.file-current {
    padding-bottom: 1rem;
    border-bottom: 1px solid #f0f0f0;
    margin-bottom: 1rem;
}

.file-current-label {
    font-size: 0.75rem;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.file-current-link {
    display: block;
    font-size: 0.875rem;
    color: #1a1a1a;
    font-weight: 500;
    text-decoration: none;
    margin-bottom: 0.25rem;
}

.file-current-link:hover {
    color: #666;
}

.file-current-meta {
    font-size: 0.8125rem;
    color: #666;
}

.file-actions {
    display: flex;
    gap: 0.5rem;
}

.file-upload-form {
    margin-top: auto;
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

.file-type-info {
    font-size: 0.75rem;
    color: #666;
    margin-top: 0.75rem;
    text-align: center;
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

.alert {
    padding: 1rem 1.25rem;
    border-radius: 8px;
    border: 1px solid #e5e5e5;
    background: #ffffff;
}

.alert-danger {
    border-color: #dc3545;
    background: #fff5f5;
    color: #dc3545;
}

.alert-secondary {
    border-color: #e5e5e5;
    background: #fafafa;
    color: #1a1a1a;
}

.alert-warning {
    border-color: #f6c23e;
    background: #faf5e6;
    color: #8b6914;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
}
</style>
@endpush