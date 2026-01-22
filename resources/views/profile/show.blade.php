@extends('layouts.' . auth()->user()->role)

@section('title', 'My Profile - EDUgate')

@section('content')
<div class="container-fluid px-4 attio-dashboard">
    <!-- Page Header -->
    <div class="page-header mb-5">
        <div>
            <h1 class="page-title">My Profile</h1>
            <p class="page-subtitle">View and manage your profile information</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button type="button" class="action-btn-primary" onclick="openAccountSettings()">
                <i class="fas fa-cog"></i>
                <span>Account Settings</span>
            </button>
            <a href="{{ route('profile.edit') }}" class="action-btn-primary">
                <i class="fas fa-edit"></i>
                <span>Edit Profile</span>
            </a>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-secondary mb-4" style="padding: 1rem 1.25rem; border-radius: 8px; border: 1px solid #e5e5e5; background: #ffffff; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <i class="fas fa-check-circle me-2"></i>
                @if(session('status') == 'profile-updated')
                    Profile updated successfully!
                @elseif(session('status') == 'password-updated')
                    Password updated successfully!
                @elseif(session('status') == 'account-updated')
                    Account settings updated successfully!
                @endif
            </div>
            <button type="button" class="modal-close" onclick="this.parentElement.remove()" style="background: none; border: none; color: #666; cursor: pointer; padding: 0.25rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Profile Overview -->
        <div class="col-lg-4">
            <div class="section-card mb-4">
                <div class="section-body text-center" style="padding: 2rem 1.5rem;">
                    <div class="mb-4">
                        @if($profile && method_exists($profile, 'getProfilePictureAttribute') && $profile->profile_picture)
                            <img src="{{ asset('storage/' . $profile->profile_picture) }}" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover; border: 2px solid #e5e5e5;">
                        @elseif($profile && isset($profile->image) && $profile->image !== 'user.png')
                            <img src="{{ asset('storage/teachers/' . $profile->image) }}" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover; border: 2px solid #e5e5e5;">
                        @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 150px; height: 150px; font-size: 3rem; background: #1a1a1a; color: #ffffff; font-weight: 600;">
                                {{ strtoupper(substr($user->name ?: 'U', 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    <h4 style="font-size: 1.5rem; font-weight: 600; color: #1a1a1a; margin-bottom: 0.5rem;">{{ $user->name ?: 'No Name Set' }}</h4>
                    <p style="color: #666; margin-bottom: 1rem; font-size: 0.9375rem;">
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
                        <p style="color: #666; margin-bottom: 1.5rem; font-size: 0.875rem;">ID: {{ $user->user_id }}</p>
                    @endif
                    
                    <div style="margin-bottom: 1.5rem;">
                        <span class="status-badge" style="background: {{ $user->status == 'Active' ? '#f0f0f0' : '#e5e5e5' }}; color: #1a1a1a;">
                            {{ $user->status ?: 'Active' }}
                        </span>
                    </div>

                    <hr style="border-color: #f0f0f0; margin: 1.5rem 0;">

                    <!-- Quick Stats -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div style="padding-right: 1rem; border-right: 1px solid #f0f0f0;">
                            <h5 style="font-size: 1.5rem; font-weight: 600; color: #1a1a1a; margin-bottom: 0.25rem;">
                                @if(auth()->user()->isTeacher() && $profile)
                                    {{ $profile->subjects ? $profile->subjects->count() : 0 }}
                                @elseif(auth()->user()->isStudent())
                                    {{ $profile->grade_level ?? $profile->grade ?? '-' }}
                                @else
                                    {{ ucfirst($user->role) }}
                                @endif
                            </h5>
                            <small style="color: #666; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                @if(auth()->user()->isTeacher())
                                    Subjects
                                @elseif(auth()->user()->isStudent())
                                    Grade
                                @else
                                    Role
                                @endif
                            </small>
                        </div>
                        <div>
                            <h5 style="font-size: 1rem; font-weight: 500; color: #1a1a1a; margin-bottom: 0.25rem;">{{ $user->created_at->diffForHumans() }}</h5>
                            <small style="color: #666; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px;">Joined</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="section-card mb-4">
                <div class="section-header">
                    <h3 class="section-title">Contact Information</h3>
                </div>
                <div class="section-body">
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">
                            <i class="fas fa-envelope" style="margin-right: 0.5rem; opacity: 0.6;"></i>
                            {{ $user->email ?: 'Not provided' }}
                        </div>
                    </div>
                    
                    @if($profile)
                        @if(isset($profile->phone))
                            <div class="info-item">
                                <div class="info-label">Phone</div>
                                <div class="info-value">
                                    <i class="fas fa-phone" style="margin-right: 0.5rem; opacity: 0.6;"></i>
                                    {{ $profile->phone ?: 'Not provided' }}
                                </div>
                            </div>
                        @endif
                        
                        @if(isset($profile->contact_number))
                            <div class="info-item">
                                <div class="info-label">Contact Number</div>
                                <div class="info-value">
                                    <i class="fas fa-mobile-alt" style="margin-right: 0.5rem; opacity: 0.6;"></i>
                                    {{ $profile->contact_number ?: 'Not provided' }}
                                </div>
                            </div>
                        @endif
                        
                        @if(isset($profile->address) || isset($profile->current_address))
                            <div class="info-item">
                                <div class="info-label">Address</div>
                                <div class="info-value">
                                    <i class="fas fa-map-marker-alt" style="margin-right: 0.5rem; opacity: 0.6;"></i>
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
            <div class="section-card mb-4">
                <div class="section-header">
                    <h3 class="section-title">Personal Information</h3>
                </div>
                <div class="section-body">
                    @if($profile)
                        <div class="info-grid">
                            @if(auth()->user()->isTeacher())
                                <div class="info-item">
                                    <div class="info-label">First Name</div>
                                    <div class="info-value">{{ $profile->firstname ?: 'Not provided' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Last Name</div>
                                    <div class="info-value">{{ $profile->lastname ?: 'Not provided' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Employee ID</div>
                                    <div class="info-value">{{ $profile->employee_id ?: 'Not assigned' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Department</div>
                                    <div class="info-value">{{ $profile->department ?: 'Not assigned' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Gender</div>
                                    <div class="info-value">{{ $profile->gender ?: 'Not specified' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Age</div>
                                    <div class="info-value">{{ $profile->age ?: 'Not specified' }}</div>
                                </div>
                            @elseif(auth()->user()->isStudent())
                                <div class="info-item">
                                    <div class="info-label">First Name</div>
                                    <div class="info-value">{{ $profile->first_name ?: $profile->firstname ?: 'Not provided' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Last Name</div>
                                    <div class="info-value">{{ $profile->last_name ?: $profile->lastname ?: 'Not provided' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">LRN Number</div>
                                    <div class="info-value">{{ $profile->lrn_number ?: 'Not assigned' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Grade Level</div>
                                    <div class="info-value">
                                        @if($profile->grade_level)
                                            Grade {{ $profile->grade_level }}
                                        @elseif($profile->grade)
                                            {{ $profile->grade }}
                                        @else
                                            Not assigned
                                        @endif
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Sex</div>
                                    <div class="info-value">{{ $profile->sex ?: $profile->gender ?: 'Not specified' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">School Year</div>
                                    <div class="info-value">{{ $profile->school_year ?: 'Not specified' }}</div>
                                </div>
                            @endif
                            
                            @if(isset($profile->date_of_birth))
                                <div class="info-item">
                                    <div class="info-label">Date of Birth</div>
                                    <div class="info-value">
                                        {{ $profile->date_of_birth ? $profile->date_of_birth->format('M d, Y') : 'Not provided' }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-user-circle"></i>
                            <h3>No Profile Information</h3>
                            <p>Complete your profile to display more information here.</p>
                            <a href="{{ route('profile.edit') }}" class="action-btn-primary">
                                <i class="fas fa-plus"></i>
                                <span>Complete Profile</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Role-specific Information -->
            @if($profile && auth()->user()->isTeacher() && $profile->subjects && $profile->subjects->count() > 0)
                <div class="section-card mb-4">
                    <div class="section-header">
                        <h3 class="section-title">Subjects Taught</h3>
                    </div>
                    <div class="section-body">
                        <div class="table-responsive" style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="border-bottom: 1px solid #f0f0f0;">
                                        <th style="padding: 0.75rem; text-align: left; font-size: 0.8125rem; font-weight: 600; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">Subject</th>
                                        <th style="padding: 0.75rem; text-align: left; font-size: 0.8125rem; font-weight: 600; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">Grade Level</th>
                                        <th style="padding: 0.75rem; text-align: left; font-size: 0.8125rem; font-weight: 600; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($profile->subjects as $subject)
                                        <tr style="border-bottom: 1px solid #f0f0f0;">
                                            <td style="padding: 0.75rem; color: #1a1a1a; font-size: 0.875rem;">{{ $subject->name }}</td>
                                            <td style="padding: 0.75rem; color: #666; font-size: 0.875rem;">{{ $subject->grade_level ?: 'All Levels' }}</td>
                                            <td style="padding: 0.75rem;"><span class="status-badge">Active</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Account Information -->
            <div class="section-card">
                <div class="section-header">
                    <h3 class="section-title">Account Information</h3>
                </div>
                <div class="section-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Username/ID</div>
                            <div class="info-value">{{ $user->user_id ?: 'Not set' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Role</div>
                            <div class="info-value">{{ ucfirst($user->role) }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Account Created</div>
                            <div class="info-value">{{ $user->created_at->format('M d, Y g:i A') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Last Updated</div>
                            <div class="info-value">{{ $user->updated_at->format('M d, Y g:i A') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Email Verification</div>
                            <div class="info-value">
                                @if($user->email_verified_at)
                                    <span class="status-badge">Verified</span>
                                @else
                                    <span class="status-badge" style="background: #faf5e6; color: #8b6914;">Unverified</span>
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
        <div class="modal-content attio-modal">
            <div class="modal-header">
                <h5 class="modal-title">Account Settings</h5>
                <button type="button" class="modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <!-- Account Settings Form will be loaded here -->
                <div id="accountSettingsContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-dark" role="status"></div>
                        <p style="color: #666; margin-top: 1rem;">Loading account settings...</p>
                    </div>
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

.action-btn-primary i {
    font-size: 0.875rem;
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
    letter-spacing: -0.01em;
}

.section-body {
    padding: 1.5rem;
}

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

.status-badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.375rem 0.75rem;
    border-radius: 4px;
    display: inline-block;
    background: #f0f0f0;
    color: #1a1a1a;
}

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

.attio-modal .modal-content {
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
}

.attio-modal .modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid #f0f0f0;
    background: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: center;
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

.btn-danger-outline {
    border-color: #dc3545;
    color: #dc3545;
}

.btn-danger-outline:hover {
    background: #dc3545;
    color: #ffffff;
    border-color: #dc3545;
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
    
    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
function openAccountSettings() {
    const modal = new bootstrap.Modal(document.getElementById('accountSettingsModal'));
    modal.show();
    
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
                    <i class="fas fa-exclamation-triangle" style="font-size: 2rem; color: #666; opacity: 0.3; margin-bottom: 1rem;"></i>
                    <p style="color: #666;">Failed to load account settings. Please try again.</p>
                    <button class="action-btn-primary" onclick="openAccountSettings()" style="margin-top: 1rem;">
                        <span>Retry</span>
                    </button>
                </div>
            `;
        });
}
</script>
@endpush
