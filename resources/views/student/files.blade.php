@extends('layouts.student')

@section('title', 'My Documents - EDUgate')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-file-alt me-2"></i>My Required Documents
            </h1>
            <p class="text-muted mb-0">Upload or replace missing/incorrect files.</p>
        </div>
        <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
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

    <div class="row">
        @foreach($requiredFiles as $type => $label)
            @php($file = $existingFiles->get($type))
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="fw-bold">{{ $label }}</span>
                        @if($file)
                            <span class="badge badge-{{ $file->status == 'approved' ? 'success' : ($file->status == 'rejected' ? 'danger' : ($file->status == 'missing' ? 'warning' : 'secondary')) }}">{{ ucfirst($file->status) }}</span>
                        @else
                            <span class="badge badge-warning">Missing</span>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($file)
                            <div class="mb-2">
                                <i class="fas fa-paperclip me-2"></i>
                                <strong>{{ $file->file_name }}</strong>
                            </div>
                            <div class="mb-3 text-muted">
                                <small>{{ $file->file_size_human }} • {{ $file->uploaded_at?->format('M d, Y') }}</small>
                            </div>
                            <div class="d-flex gap-2 mb-3">
                                <a class="btn btn-outline-primary btn-sm" href="{{ route('student.files.view', $file) }}" target="_blank">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                                <form method="POST" action="{{ route('student.files.destroy', $file) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this file?')">
                                        <i class="fas fa-trash me-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                            <form method="POST" action="{{ route('student.files.update', $file) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <div class="input-group">
                                    <input type="file" name="file" class="form-control" accept="application/pdf,image/*" required>
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-upload me-1"></i>Replace
                                    </button>
                                </div>
                                <small class="text-muted d-block mt-1">PDF/JPG/PNG, max 5MB</small>
                            </form>
                        @else
                            <form method="POST" action="{{ route('student.files.store') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="file_type" value="{{ $type }}">
                                <div class="input-group">
                                    <input type="file" name="file" class="form-control" accept="application/pdf,image/*" required>
                                    <button class="btn btn-success" type="submit">
                                        <i class="fas fa-upload me-1"></i>Upload
                                    </button>
                                </div>
                                <small class="text-muted d-block mt-1">PDF/JPG/PNG, max 5MB</small>
                            </form>
                        @endif
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


