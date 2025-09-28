<div>
    <p class="text-muted mb-4">
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
                <label for="name" class="form-label">Full Name</label>
                <input type="text" 
                       class="form-control @error('name') is-invalid @enderror" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $user->name) }}" 
                       required 
                       autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       id="email" 
                       name="email" 
                       value="{{ old('email', $user->email) }}" 
                       required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <small class="text-warning">
                            Your email address is unverified.
                            <button form="send-verification" class="btn btn-link btn-sm p-0 text-warning">
                                Click here to re-send the verification email.
                            </button>
                        </small>

                        @if (session('status') === 'verification-link-sent')
                            <div class="mt-1">
                                <small class="text-success">
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
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" 
                       class="form-control @error('phone') is-invalid @enderror" 
                       id="phone" 
                       name="phone" 
                       value="{{ old('phone', $user->phone) }}">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="department" class="form-label">Department</label>
                <input type="text" 
                       class="form-control @error('department') is-invalid @enderror" 
                       id="department" 
                       name="department" 
                       value="{{ old('department', $user->department) }}">
                @error('department')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="employee_id" class="form-label">Employee ID</label>
                <input type="text" 
                       class="form-control @error('employee_id') is-invalid @enderror" 
                       id="employee_id" 
                       name="employee_id" 
                       value="{{ old('employee_id', $user->employee_id) }}">
                @error('employee_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex align-items-center">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Save Changes
            </button>

            @if (session('status') === 'profile-updated')
                <span class="text-success ms-3">
                    <i class="fas fa-check me-1"></i>Saved successfully!
                </span>
            @endif
        </div>
    </form>
</div>
