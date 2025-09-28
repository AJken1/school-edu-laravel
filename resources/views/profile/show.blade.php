@extends('layouts.' . auth()->user()->role)

@section('title', 'My Profile - EDUgate')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center py-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">My Profile</h1>
            <p class="text-muted mb-0">View and manage your profile information</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary me-2" onclick="openAccountSettings()">
                <i class="fas fa-cog me-2"></i>Account Settings
            </button>
            <a href="{{ route('profile.edit') }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit Profile
            </a>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            @if(session('status') == 'profile-updated')
                Profile updated successfully!
            @elseif(session('status') == 'password-updated')
                Password updated successfully!
            @elseif(session('status') == 'account-updated')
                Account settings updated successfully!
            @endif
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Profile Overview -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <div class="mb-4">
                        @if($profile && method_exists($profile, 'getProfilePictureAttribute') && $profile->profile_picture)
                            <img src="{{ asset('storage/' . $profile->profile_picture) }}" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        @elseif($profile && isset($profile->image) && $profile->image !== 'user.png')
                            <img src="{{ asset('storage/teachers/' . $profile->image) }}" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="bg-{{ auth()->user()->role == 'admin' ? 'danger' : (auth()->user()->role == 'teacher' ? 'success' : 'primary') }} text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 150px; height: 150px; font-size: 3rem;">
                                {{ strtoupper(substr($user->name ?: 'U', 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    <h4 class="mb-1">{{ $user->name ?: 'No Name Set' }}</h4>
                    <p class="text-muted mb-2">
                        @if($profile)
                            @if(auth()->user()->isTeacher() && $profile->position)
                                {{ $profile->position }}
                            @elseif(auth()->user()->isStudent() && isset($profile->grade))
                                Grade {{ $profile->grade_level ?: $profile->grade }}
                            @elseif(auth()->user()->isAdmin())
                                Administrator
                            @else
                                {{ ucfirst(auth()->user()->role) }}
                            @endif
                        @else
                            {{ ucfirst(auth()->user()->role) }}
                        @endif
                    </p>
                    
                    @if($user->user_id)
                        <p class="text-muted mb-3">ID: {{ $user->user_id }}</p>
                    @endif
                    
                    <div class="row text-center">
                        <div class="col">
                            <span class="badge badge-{{ $user->status == 'Active' ? 'success' : 'secondary' }} badge-lg">
                                {{ $user->status ?: 'Active' }}
                            </span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Quick Stats -->
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-right">
                                <h5 class="mb-0">
                                    @if(auth()->user()->isTeacher() && $profile)
                                        {{ $profile->subjects ? $profile->subjects->count() : 0 }}
                                    @elseif(auth()->user()->isStudent())
                                        {{ $profile->grade_level ?? $profile->grade ?? '-' }}
                                    @else
                                        {{ ucfirst($user->role) }}
                                    @endif
                                </h5>
                                <small class="text-muted">
                                    @if(auth()->user()->isTeacher())
                                        Subjects
                                    @elseif(auth()->user()->isStudent())
                                        Grade
                                    @else
                                        Role
                                    @endif
                                </small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h5 class="mb-0">{{ $user->created_at->diffForHumans() }}</h5>
                            <small class="text-muted">Joined</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Contact Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Email</label>
                        <div class="font-weight-bold">
                            <i class="fas fa-envelope text-muted me-2"></i>
                            {{ $user->email ?: 'Not provided' }}
                        </div>
                    </div>
                    
                    @if($profile)
                        @if(isset($profile->phone))
                            <div class="mb-3">
                                <label class="text-muted small">Phone</label>
                                <div class="font-weight-bold">
                                    <i class="fas fa-phone text-muted me-2"></i>
                                    {{ $profile->phone ?: 'Not provided' }}
                                </div>
                            </div>
                        @endif
                        
                        @if(isset($profile->contact_number))
                            <div class="mb-3">
                                <label class="text-muted small">Contact Number</label>
                                <div class="font-weight-bold">
                                    <i class="fas fa-mobile-alt text-muted me-2"></i>
                                    {{ $profile->contact_number ?: 'Not provided' }}
                                </div>
                            </div>
                        @endif
                        
                        @if(isset($profile->address) || isset($profile->current_address))
                            <div class="mb-0">
                                <label class="text-muted small">Address</label>
                                <div class="font-weight-bold">
                                    <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                    {{ $profile->address ?: $profile->current_address ?: 'Not provided' }}
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Detailed Information -->
        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Personal Information</h6>
                </div>
                <div class="card-body">
                    @if($profile)
                        <div class="row">
                            @if(auth()->user()->isTeacher())
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">First Name</label>
                                    <div class="font-weight-bold">{{ $profile->firstname ?: 'Not provided' }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Last Name</label>
                                    <div class="font-weight-bold">{{ $profile->lastname ?: 'Not provided' }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Employee ID</label>
                                    <div class="font-weight-bold">{{ $profile->employee_id ?: 'Not assigned' }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Department</label>
                                    <div class="font-weight-bold">{{ $profile->department ?: 'Not assigned' }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Gender</label>
                                    <div class="font-weight-bold">{{ $profile->gender ?: 'Not specified' }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Age</label>
                                    <div class="font-weight-bold">{{ $profile->age ?: 'Not specified' }}</div>
                                </div>
                            @elseif(auth()->user()->isStudent())
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">First Name</label>
                                    <div class="font-weight-bold">{{ $profile->first_name ?: $profile->firstname ?: 'Not provided' }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Last Name</label>
                                    <div class="font-weight-bold">{{ $profile->last_name ?: $profile->lastname ?: 'Not provided' }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">LRN Number</label>
                                    <div class="font-weight-bold">{{ $profile->lrn_number ?: 'Not assigned' }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Grade Level</label>
                                    <div class="font-weight-bold">
                                        @if($profile->grade_level)
                                            Grade {{ $profile->grade_level }}
                                        @elseif($profile->grade)
                                            {{ $profile->grade }}
                                        @else
                                            Not assigned
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Sex</label>
                                    <div class="font-weight-bold">{{ $profile->sex ?: $profile->gender ?: 'Not specified' }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">School Year</label>
                                    <div class="font-weight-bold">{{ $profile->school_year ?: 'Not specified' }}</div>
                                </div>
                            @endif
                            
                            @if(isset($profile->date_of_birth))
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Date of Birth</label>
                                    <div class="font-weight-bold">
                                        {{ $profile->date_of_birth ? $profile->date_of_birth->format('M d, Y') : 'Not provided' }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-circle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Profile Information</h5>
                            <p class="text-muted">Complete your profile to display more information here.</p>
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Complete Profile
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Role-specific Information -->
            @if($profile && auth()->user()->isTeacher() && $profile->subjects && $profile->subjects->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Subjects Taught</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Grade Level</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($profile->subjects as $subject)
                                        <tr>
                                            <td>{{ $subject->name }}</td>
                                            <td>{{ $subject->grade_level ?: 'All Levels' }}</td>
                                            <td><span class="badge badge-success">Active</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Account Information -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Account Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Username/ID</label>
                            <div class="font-weight-bold">{{ $user->user_id ?: 'Not set' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Role</label>
                            <div class="font-weight-bold">{{ ucfirst($user->role) }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Account Created</label>
                            <div class="font-weight-bold">{{ $user->created_at->format('M d, Y g:i A') }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Last Updated</label>
                            <div class="font-weight-bold">{{ $user->updated_at->format('M d, Y g:i A') }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Email Verification</label>
                            <div class="font-weight-bold">
                                @if($user->email_verified_at)
                                    <span class="badge badge-success">Verified</span>
                                @else
                                    <span class="badge badge-warning">Unverified</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Account Settings Modal -->
<div class="modal fade" id="accountSettingsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-cog me-2"></i>Account Settings
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Account Settings Form will be loaded here -->
                <div id="accountSettingsContent">
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin fa-2x text-muted mb-3"></i>
                        <p class="text-muted">Loading account settings...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openAccountSettings() {
    $('#accountSettingsModal').modal('show');
    
    // Load account settings content
    fetch('{{ route("account.settings") }}')
        .then(response => response.text())
        .then(html => {
            document.getElementById('accountSettingsContent').innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading account settings:', error);
            document.getElementById('accountSettingsContent').innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle fa-2x text-warning mb-3"></i>
                    <p class="text-muted">Failed to load account settings. Please try again.</p>
                    <button class="btn btn-primary" onclick="openAccountSettings()">Retry</button>
                </div>
            `;
        });
}
</script>
@endpush
