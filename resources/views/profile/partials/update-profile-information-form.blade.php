<div>
    <p style="color: #666; font-size: 0.875rem; margin-bottom: 1.5rem;">
        Update your account's profile information and email address.
    </p>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="attio-label">Full Name</label>
                <input type="text" 
                       class="attio-input @error('name') is-invalid @enderror" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $user->name) }}" 
                       required 
                       autofocus>
                @error('name')
                    <div class="invalid-feedback" style="font-size: 0.8125rem; color: #dc3545; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="email" class="attio-label">Email Address</label>
                <input type="email" 
                       class="attio-input @error('email') is-invalid @enderror" 
                       id="email" 
                       name="email" 
                       value="{{ old('email', $user->email) }}" 
                       required>
                @error('email')
                    <div class="invalid-feedback" style="font-size: 0.8125rem; color: #dc3545; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div style="margin-top: 0.75rem; padding: 0.75rem; background: #faf5e6; border: 1px solid #e5e5e5; border-radius: 6px;">
                        <small style="color: #8b6914; font-size: 0.8125rem;">
                            Your email address is unverified.
                            <button form="send-verification" class="btn-link" style="background: none; border: none; color: #8b6914; text-decoration: underline; cursor: pointer; padding: 0; font-size: inherit;">
                                Click here to re-send the verification email.
                            </button>
                        </small>

                        @if (session('status') === 'verification-link-sent')
                            <div style="margin-top: 0.5rem;">
                                <small style="color: #1a1a1a; font-size: 0.8125rem;">
                                    A new verification link has been sent to your email address.
                                </small>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="phone" class="attio-label">Phone Number</label>
                <input type="text" 
                       class="attio-input @error('phone') is-invalid @enderror" 
                       id="phone" 
                       name="phone" 
                       value="{{ old('phone', $user->phone) }}">
                @error('phone')
                    <div class="invalid-feedback" style="font-size: 0.8125rem; color: #dc3545; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="department" class="attio-label">Department</label>
                <input type="text" 
                       class="attio-input @error('department') is-invalid @enderror" 
                       id="department" 
                       name="department" 
                       value="{{ old('department', $user->department) }}">
                @error('department')
                    <div class="invalid-feedback" style="font-size: 0.8125rem; color: #dc3545; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="employee_id" class="attio-label">Employee ID</label>
                <input type="text" 
                       class="attio-input @error('employee_id') is-invalid @enderror" 
                       id="employee_id" 
                       name="employee_id" 
                       value="{{ old('employee_id', $user->employee_id) }}">
                @error('employee_id')
                    <div class="invalid-feedback" style="font-size: 0.8125rem; color: #dc3545; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex align-items-center" style="margin-top: 1.5rem;">
            <button type="submit" class="action-btn-primary">
                <i class="fas fa-save"></i>
                <span>Save Changes</span>
            </button>

            @if (session('status') === 'profile-updated')
                <span style="color: #1a1a1a; margin-left: 1rem; font-size: 0.875rem; display: flex; align-items: center; gap: 0.375rem;">
                    <i class="fas fa-check"></i>Saved successfully!
                </span>
            @endif
        </div>
    </form>
</div>
