@extends('layouts.' . auth()->user()->role)

@section('title', 'Profile Settings - EDUgate Admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center py-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Edit Profile</h1>
            <p class="text-muted mb-0">Update your detailed profile information</p>
        </div>
        <a href="{{ route('profile.show') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Profile
        </a>
    </div>

    <!-- Success Messages -->
    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            @if(session('status') == 'profile-updated')
                Your profile information has been updated successfully.
            @elseif(session('status') == 'password-updated')
                Your password has been updated successfully.
            @endif
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user me-2"></i>Profile Information
                    </h6>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <!-- Account Security -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-shield-alt me-2"></i>Account Security
                    </h6>
                </div>
                <div class="card-body">
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
                            <img src="{{ $profileImage }}" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="bg-{{ auth()->user()->role == 'admin' ? 'danger' : (auth()->user()->role == 'teacher' ? 'success' : 'primary') }} text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2rem;">
                                {{ strtoupper(substr(auth()->user()->name ?: 'U', 0, 1)) }}
                            </div>
                        @endif
                        
                        <h5>{{ auth()->user()->name }}</h5>
                        <p class="text-muted">{{ ucfirst(auth()->user()->role) }}</p>
                        <span class="badge badge-success">{{ ucfirst(auth()->user()->status ?: 'Active') }}</span>
                    </div>
                    
                    <div class="border-top pt-3">
                        <small class="text-muted">
                            <i class="fas fa-envelope me-2"></i>{{ auth()->user()->email }}<br>
                            <i class="fas fa-id-badge me-2"></i>{{ auth()->user()->employee_id ?: 'Not assigned' }}<br>
                            <i class="fas fa-calendar me-2"></i>Joined {{ auth()->user()->created_at->format('M Y') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Update Password -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-key me-2"></i>Update Password
                    </h6>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow border-left-danger">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>Danger Zone
                    </h6>
                </div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
