@extends('layouts.teacher')

@section('title', 'My Files - Teacher Portal')

@section('content')
<div class="container-fluid px-4 attio-dashboard">
    <!-- Page Header -->
    <div class="page-header mb-5">
        <div>
            <h1 class="page-title">My Required Documents</h1>
            <p class="page-subtitle">Upload or replace missing/incorrect files.</p>
        </div>
        <a href="{{ route('teacher.dashboard') }}" class="action-btn-primary">
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
        @php
            $documentTypes = [
                'lesson_plan' => ['name' => 'Lesson Plan', 'icon' => 'fa-chalkboard', 'accept' => '.pdf,.doc,.docx,.txt', 'extensions' => 'DOC/PDF/TXT'],
                'resource' => ['name' => 'Teaching Resources', 'icon' => 'fa-folder-open', 'accept' => '.pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar', 'extensions' => 'DOC/PDF/PPT/XLS/ZIP'],
                'certificate' => ['name' => 'Teaching Certificate', 'icon' => 'fa-certificate', 'accept' => '.pdf,.jpg,.jpeg,.png', 'extensions' => 'PDF/JPG/PNG'],
                'photo' => ['name' => 'Profile Photo', 'icon' => 'fa-user-circle', 'accept' => '.jpg,.jpeg,.png,.gif', 'extensions' => 'JPG/PNG/GIF'],
                'education' => ['name' => 'Educational Documents', 'icon' => 'fa-graduation-cap', 'accept' => '.pdf,.doc,.docx,.jpg,.jpeg,.png', 'extensions' => 'PDF/DOC/JPG/PNG'],
                'other' => ['name' => 'Other Documents', 'icon' => 'fa-file-alt', 'accept' => '.pdf,.doc,.docx,.txt,.jpg,.jpeg,.png', 'extensions' => 'PDF/DOC/TXT/JPG/PNG']
            ];
        @endphp

        @foreach($documentTypes as $category => $docInfo)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="section-card">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas {{ $docInfo['icon'] }}"></i>
                        {{ $docInfo['name'] }}
                    </h3>
                </div>
                <div class="section-body">
                    @php
                        $file = $files->where('category', $category)->first();
                    @endphp
                    
                    @if($file)
                        <div class="file-current mb-3">
                            <div class="file-current-label">Current File:</div>
                            <a href="{{ route('teacher.files.view', $file) }}" class="file-current-link" target="_blank">
                                {{ $file->original_name }}
                            </a>
                            <div class="file-current-meta">
                                {{ number_format($file->file_size / 1024, 1) }} KB • {{ $file->created_at->format('M d, Y') }}
                            </div>
                            <div class="file-actions mt-2">
                                <a href="{{ route('teacher.files.view', $file) }}" class="action-btn-secondary btn-sm" target="_blank">
                                    <i class="fas fa-eye"></i>
                                    <span>View</span>
                                </a>
                                <button type="button" class="action-btn-secondary btn-sm btn-danger-outline" onclick="deleteFile({{ $file->id }})">
                                    <i class="fas fa-trash"></i>
                                    <span>Delete</span>
                                </button>
                            </div>
                        </div>
                    @endif
                    
                    <form action="{{ route('teacher.files.store') }}" method="POST" enctype="multipart/form-data" class="file-upload-form">
                        @csrf
                        <input type="hidden" name="category" value="{{ $category }}">
                        <div class="mb-3">
                            <input type="file" class="form-control attio-input" name="files[]" accept="{{ $docInfo['accept'] }}" required>
                        </div>
                        <button type="submit" class="action-btn-primary w-100">
                            <i class="fas {{ $file ? 'fa-sync' : 'fa-upload' }}"></i>
                            <span>{{ $file ? 'Replace' : 'Upload' }}</span>
                        </button>
                    </form>
                    <div class="file-type-info">{{ $docInfo['extensions'] }}, max 10MB</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content attio-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this file? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="action-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="action-btn-primary btn-danger-outline" id="confirmDeleteBtn">Delete</button>
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

.action-btn-primary.btn-danger-outline {
    border-color: #dc3545;
    color: #dc3545;
    background: #ffffff;
}

.action-btn-primary.btn-danger-outline:hover {
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

.section-title i {
    font-size: 0.875rem;
    opacity: 0.7;
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
    font-size: 1.125rem;
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
    color: #666;
    font-size: 0.9375rem;
}

.attio-modal .modal-footer {
    padding: 1.5rem;
    border-top: 1px solid #f0f0f0;
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
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

@push('scripts')
<script>
let deleteFileId = null;
const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

function deleteFile(fileId) {
    deleteFileId = fileId;
    deleteModal.show();
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (deleteFileId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/teacher/files/${deleteFileId}`;
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        form.innerHTML = `
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
    }
});

// Handle form submissions with loading states
document.querySelectorAll('.file-upload-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Uploading...</span>';
        submitBtn.disabled = true;
        
        // Re-enable after 10 seconds as fallback
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 10000);
    });
});
</script>
@endpush
