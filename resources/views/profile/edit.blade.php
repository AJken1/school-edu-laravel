@extends('layouts.' . auth()->user()->role)

@section('title', 'Profile Settings - EDUgate Admin')

@section('content')
<div class="container-fluid px-4 attio-dashboard">
    <!-- Page Header -->
    <div class="page-header mb-5">
        <div>
            <h1 class="page-title">Edit Profile</h1>
            <p class="page-subtitle">Update your detailed profile information</p>
        </div>
        <a href="{{ route('profile.show') }}" class="action-btn-primary">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Profile</span>
        </a>
    </div>

    <!-- Success Messages -->
    @if(session('status'))
        <div class="alert alert-secondary mb-4" style="padding: 1rem 1.25rem; border-radius: 8px; border: 1px solid #e5e5e5; background: #ffffff; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <i class="fas fa-check-circle me-2"></i>
                @if(session('status') == 'profile-updated')
                    Your profile information has been updated successfully.
                @elseif(session('status') == 'password-updated')
                    Your password has been updated successfully.
                @endif
            </div>
            <button type="button" class="modal-close" onclick="this.parentElement.remove()" style="background: none; border: none; color: #666; cursor: pointer; padding: 0.25rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-8 mb-4">
            <div class="section-card">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-user"></i>
                        Profile Information
                    </h3>
                </div>
                <div class="section-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <!-- Account Security -->
        <div class="col-lg-4 mb-4">
            <div class="section-card">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-shield-alt"></i>
                        Account Security
                    </h3>
                </div>
                <div class="section-body">
                    <div class="text-center mb-3">
                        @php
                            $profile = auth()->user()->profile();
                            $profileImage = null;
                            if ($profile) {
                                if (method_exists($profile, 'getProfilePictureAttribute') && $profile->profile_picture) {
                                    $profileImage = asset('storage/' . $profile->profile_picture);
                                } elseif (isset($profile->image) && $profile->image !== 'user.png') {
                                    $profileImage = asset('storage/teachers/' . $profile->image);
                                }
                            }
                        @endphp
                        
                        @if($profileImage)
                            <img src="{{ $profileImage }}" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover; border: 2px solid #e5e5e5;">
                        @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2rem; background: #1a1a1a; color: #ffffff; font-weight: 600;">
                                {{ strtoupper(substr(auth()->user()->name ?: 'U', 0, 1)) }}
                            </div>
                        @endif
                        
                        <h5 style="font-size: 1.125rem; font-weight: 600; color: #1a1a1a; margin-bottom: 0.25rem;">{{ auth()->user()->name }}</h5>
                        <p style="color: #666; margin-bottom: 0.5rem; font-size: 0.875rem;">{{ ucfirst(auth()->user()->role) }}</p>
                        <span class="status-badge">{{ ucfirst(auth()->user()->status ?: 'Active') }}</span>
                    </div>
                    
                    <div style="border-top: 1px solid #f0f0f0; padding-top: 1rem;">
                        <div style="margin-bottom: 0.5rem; font-size: 0.8125rem; color: #666;">
                            <i class="fas fa-envelope" style="margin-right: 0.5rem; opacity: 0.6;"></i>{{ auth()->user()->email }}
                        </div>
                        <div style="margin-bottom: 0.5rem; font-size: 0.8125rem; color: #666;">
                            <i class="fas fa-id-badge" style="margin-right: 0.5rem; opacity: 0.6;"></i>{{ auth()->user()->employee_id ?: 'Not assigned' }}
                        </div>
                        <div style="font-size: 0.8125rem; color: #666;">
                            <i class="fas fa-calendar" style="margin-right: 0.5rem; opacity: 0.6;"></i>Joined {{ auth()->user()->created_at->format('M Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Update Password -->
        <div class="col-lg-6 mb-4">
            <div class="section-card">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-key"></i>
                        Update Password
                    </h3>
                </div>
                <div class="section-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="col-lg-6 mb-4">
            <div class="section-card" style="border-left: 3px solid #dc3545;">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-exclamation-triangle"></i>
                        Danger Zone
                    </h3>
                </div>
                <div class="section-body">
                    @include('profile.partials.delete-user-form')
                </div>
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

.section-title i {
    font-size: 0.875rem;
    opacity: 0.7;
}

.section-body {
    padding: 1.5rem;
}

.attio-label {
    font-size: 0.8125rem;
    color: #666;
    font-weight: 500;
    margin-bottom: 0.5rem;
    display: block;
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

.status-badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.375rem 0.75rem;
    border-radius: 4px;
    display: inline-block;
    background: #f0f0f0;
    color: #1a1a1a;
}

.btn-danger-outline {
    border-color: #dc3545;
    color: #dc3545;
}

.btn-danger-outline:hover {
    background: #dc3545;
    color: #ffffff;
    border-color: #dc3545;
}

.attio-modal .modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #f0f0f0;
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
}

@media (max-width: 768px) {
    .attio-dashboard {
        padding: 1rem 0;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .page-header {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>
@endpush
