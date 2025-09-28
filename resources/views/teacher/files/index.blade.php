@extends('layouts.teacher')

@section('title', 'My Files - Teacher Portal')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header (match student files style) -->
    <div class="d-flex justify-content-between align-items-center py-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-file-alt me-2"></i>My Required Documents
            </h1>
            <p class="text-muted mb-0">Upload or replace missing/incorrect files.</p>
        </div>
        <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back To Dashboard
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Document Cards Grid -->
    <div class="row">
        <!-- Lesson Plan Card -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-chalkboard me-2"></i>Lesson Plan
            </h6>
        </div>
        <div class="card-body">
                    @php
                        $lessonPlan = $files->where('category', 'lesson_plan')->first();
                    @endphp
                    
                    @if($lessonPlan)
                        <div class="mb-3">
                            <p class="mb-1"><strong>Current File:</strong></p>
                            <a href="{{ route('teacher.files.view', $lessonPlan) }}" class="text-primary" target="_blank">
                                {{ $lessonPlan->original_name }}
                            </a>
                            <small class="d-block text-muted">
                                {{ number_format($lessonPlan->file_size / 1024, 1) }} KB • {{ $lessonPlan->created_at->format('M d, Y') }}
                            </small>
                </div>
                        <div class="d-flex gap-2 mb-3">
                            <a href="{{ route('teacher.files.view', $lessonPlan) }}" class="btn btn-info btn-sm" target="_blank">
                                <i class="fas fa-eye me-1"></i>View
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteFile({{ $lessonPlan->id }})">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                </div>
                    @endif
                    
                    <form action="{{ route('teacher.files.store') }}" method="POST" enctype="multipart/form-data" class="file-upload-form">
                        @csrf
                        <input type="hidden" name="category" value="lesson_plan">
                        <div class="mb-3">
                            <input type="file" class="form-control" name="files[]" accept=".pdf,.doc,.docx,.txt" required>
                        </div>
                        <button type="submit" class="btn {{ $lessonPlan ? 'btn-primary' : 'btn-success' }} w-100">
                            <i class="fas {{ $lessonPlan ? 'fa-sync' : 'fa-upload' }} me-2"></i>
                            {{ $lessonPlan ? 'Replace' : 'Upload' }}
                        </button>
                    </form>
                    <small class="text-muted">DOC/PDF/TXT, max 10MB</small>
            </div>
        </div>
    </div>

        <!-- Teaching Resources Card -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-folder-open me-2"></i>Teaching Resources
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $resource = $files->where('category', 'resource')->first();
                    @endphp
                    
                    @if($resource)
                        <div class="mb-3">
                            <p class="mb-1"><strong>Current File:</strong></p>
                            <a href="{{ route('teacher.files.view', $resource) }}" class="text-primary" target="_blank">
                                {{ $resource->original_name }}
                            </a>
                            <small class="d-block text-muted">
                                {{ number_format($resource->file_size / 1024, 1) }} KB • {{ $resource->created_at->format('M d, Y') }}
                            </small>
                        </div>
                        <div class="d-flex gap-2 mb-3">
                            <a href="{{ route('teacher.files.view', $resource) }}" class="btn btn-info btn-sm" target="_blank">
                                <i class="fas fa-eye me-1"></i>View
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteFile({{ $resource->id }})">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </div>
                    @endif
                    
                    <form action="{{ route('teacher.files.store') }}" method="POST" enctype="multipart/form-data" class="file-upload-form">
                        @csrf
                        <input type="hidden" name="category" value="resource">
                        <div class="mb-3">
                            <input type="file" class="form-control" name="files[]" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar" required>
                        </div>
                        <button type="submit" class="btn {{ $resource ? 'btn-primary' : 'btn-success' }} w-100">
                            <i class="fas {{ $resource ? 'fa-sync' : 'fa-upload' }} me-2"></i>
                            {{ $resource ? 'Replace' : 'Upload' }}
                        </button>
                    </form>
                    <small class="text-muted">DOC/PDF/PPT/XLS/ZIP, max 10MB</small>
                </div>
            </div>
        </div>

        <!-- Teaching Certificate Card -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-certificate me-2"></i>Teaching Certificate
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $certificate = $files->where('category', 'certificate')->first();
                    @endphp
                    
                    @if($certificate)
                        <div class="mb-3">
                            <p class="mb-1"><strong>Current File:</strong></p>
                            <a href="{{ route('teacher.files.view', $certificate) }}" class="text-primary" target="_blank">
                                {{ $certificate->original_name }}
                            </a>
                            <small class="d-block text-muted">
                                {{ number_format($certificate->file_size / 1024, 1) }} KB • {{ $certificate->created_at->format('M d, Y') }}
                            </small>
                        </div>
                        <div class="d-flex gap-2 mb-3">
                            <a href="{{ route('teacher.files.view', $certificate) }}" class="btn btn-info btn-sm" target="_blank">
                                <i class="fas fa-eye me-1"></i>View
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteFile({{ $certificate->id }})">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </div>
                    @endif
                    
                    <form action="{{ route('teacher.files.store') }}" method="POST" enctype="multipart/form-data" class="file-upload-form">
                        @csrf
                        <input type="hidden" name="category" value="certificate">
                        <div class="mb-3">
                            <input type="file" class="form-control" name="files[]" accept=".pdf,.jpg,.jpeg,.png" required>
                        </div>
                        <button type="submit" class="btn {{ $certificate ? 'btn-primary' : 'btn-success' }} w-100">
                            <i class="fas {{ $certificate ? 'fa-sync' : 'fa-upload' }} me-2"></i>
                            {{ $certificate ? 'Replace' : 'Upload' }}
                        </button>
                    </form>
                    <small class="text-muted">PDF/JPG/PNG, max 10MB</small>
                </div>
            </div>
        </div>

        <!-- Profile Photo Card -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-warning text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i>Profile Photo
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $photo = $files->where('category', 'photo')->first();
                    @endphp
                    
                    @if($photo)
                        <div class="mb-3">
                            <p class="mb-1"><strong>Current File:</strong></p>
                            <a href="{{ route('teacher.files.view', $photo) }}" class="text-primary" target="_blank">
                                {{ $photo->original_name }}
                            </a>
                            <small class="d-block text-muted">
                                {{ number_format($photo->file_size / 1024, 1) }} KB • {{ $photo->created_at->format('M d, Y') }}
                            </small>
                        </div>
                        <div class="d-flex gap-2 mb-3">
                            <a href="{{ route('teacher.files.view', $photo) }}" class="btn btn-info btn-sm" target="_blank">
                                <i class="fas fa-eye me-1"></i>View
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteFile({{ $photo->id }})">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </div>
                    @endif
                    
                    <form action="{{ route('teacher.files.store') }}" method="POST" enctype="multipart/form-data" class="file-upload-form">
                        @csrf
                        <input type="hidden" name="category" value="photo">
                        <div class="mb-3">
                            <input type="file" class="form-control" name="files[]" accept=".jpg,.jpeg,.png,.gif" required>
                        </div>
                        <button type="submit" class="btn {{ $photo ? 'btn-primary' : 'btn-success' }} w-100">
                            <i class="fas {{ $photo ? 'fa-sync' : 'fa-upload' }} me-2"></i>
                            {{ $photo ? 'Replace' : 'Upload' }}
                        </button>
                    </form>
                    <small class="text-muted">JPG/PNG/GIF, max 10MB</small>
                </div>
            </div>
        </div>

        <!-- Educational Documents Card -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-graduation-cap me-2"></i>Educational Documents
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $education = $files->where('category', 'education')->first();
                    @endphp
                    
                    @if($education)
                        <div class="mb-3">
                            <p class="mb-1"><strong>Current File:</strong></p>
                            <a href="{{ route('teacher.files.view', $education) }}" class="text-primary" target="_blank">
                                {{ $education->original_name }}
                            </a>
                            <small class="d-block text-muted">
                                {{ number_format($education->file_size / 1024, 1) }} KB • {{ $education->created_at->format('M d, Y') }}
                            </small>
                        </div>
                        <div class="d-flex gap-2 mb-3">
                            <a href="{{ route('teacher.files.view', $education) }}" class="btn btn-info btn-sm" target="_blank">
                                <i class="fas fa-eye me-1"></i>View
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteFile({{ $education->id }})">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </div>
                    @endif
                    
                    <form action="{{ route('teacher.files.store') }}" method="POST" enctype="multipart/form-data" class="file-upload-form">
                        @csrf
                        <input type="hidden" name="category" value="education">
                        <div class="mb-3">
                            <input type="file" class="form-control" name="files[]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                        </div>
                        <button type="submit" class="btn {{ $education ? 'btn-primary' : 'btn-success' }} w-100">
                            <i class="fas {{ $education ? 'fa-sync' : 'fa-upload' }} me-2"></i>
                            {{ $education ? 'Replace' : 'Upload' }}
                        </button>
                    </form>
                    <small class="text-muted">PDF/DOC/JPG/PNG, max 10MB</small>
            </div>
        </div>
    </div>

        <!-- Other Documents Card -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>Other Documents
            </h6>
                </div>
                <div class="card-body">
                    @php
                        $other = $files->where('category', 'other')->first();
                    @endphp
                    
                    @if($other)
                        <div class="mb-3">
                            <p class="mb-1"><strong>Current File:</strong></p>
                            <a href="{{ route('teacher.files.view', $other) }}" class="text-primary" target="_blank">
                                {{ $other->original_name }}
                            </a>
                            <small class="d-block text-muted">
                                {{ number_format($other->file_size / 1024, 1) }} KB • {{ $other->created_at->format('M d, Y') }}
                            </small>
                        </div>
                        <div class="d-flex gap-2 mb-3">
                            <a href="{{ route('teacher.files.view', $other) }}" class="btn btn-info btn-sm" target="_blank">
                                <i class="fas fa-eye me-1"></i>View
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteFile({{ $other->id }})">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </div>
                    @endif
                    
                    <form action="{{ route('teacher.files.store') }}" method="POST" enctype="multipart/form-data" class="file-upload-form">
                        @csrf
                        <input type="hidden" name="category" value="other">
                        <div class="mb-3">
                            <input type="file" class="form-control" name="files[]" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png" required>
                        </div>
                        <button type="submit" class="btn {{ $other ? 'btn-primary' : 'btn-success' }} w-100">
                            <i class="fas {{ $other ? 'fa-sync' : 'fa-upload' }} me-2"></i>
                            {{ $other ? 'Replace' : 'Upload' }}
                        </button>
                    </form>
                    <small class="text-muted">PDF/DOC/TXT/JPG/PNG, max 10MB</small>
                    </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this file? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
    font-weight: 600;
}

.file-upload-form {
    margin-top: auto;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.text-muted {
    color: #6c757d !important;
    font-size: 0.875rem;
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
        form.innerHTML = `
            @csrf
            @method('DELETE')
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
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
        submitBtn.disabled = true;
        
        // Re-enable after 5 seconds as fallback
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });
});
</script>
@endpush