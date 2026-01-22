<div>
    <p style="color: #666; font-size: 0.875rem; margin-bottom: 1.5rem;">
        Ensure your account is using a long, random password to stay secure.
    </p>

    <form method="post" action="{{ route('account.password.update') }}">
        @csrf

        <div class="mb-3">
            <label for="current_password" class="attio-label">Current Password</label>
            <input type="password" 
                   class="attio-input @error('current_password') is-invalid @enderror" 
                   id="current_password" 
                   name="current_password" 
                   autocomplete="current-password">
            @error('current_password')
                <div class="invalid-feedback" style="font-size: 0.8125rem; color: #dc3545; margin-top: 0.25rem;">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="attio-label">New Password</label>
            <input type="password" 
                   class="attio-input @error('password') is-invalid @enderror" 
                   id="password" 
                   name="password" 
                   autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback" style="font-size: 0.8125rem; color: #dc3545; margin-top: 0.25rem;">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="attio-label">Confirm New Password</label>
            <input type="password" 
                   class="attio-input @error('password_confirmation') is-invalid @enderror" 
                   id="password_confirmation" 
                   name="password_confirmation" 
                   autocomplete="new-password">
            @error('password_confirmation')
                <div class="invalid-feedback" style="font-size: 0.8125rem; color: #dc3545; margin-top: 0.25rem;">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center" style="margin-top: 1.5rem;">
            <button type="submit" class="action-btn-primary">
                <i class="fas fa-lock"></i>
                <span>Update Password</span>
            </button>

            @if (session('status') === 'password-updated')
                <span style="color: #1a1a1a; margin-left: 1rem; font-size: 0.875rem; display: flex; align-items: center; gap: 0.375rem;">
                    <i class="fas fa-check"></i>Password updated successfully!
                </span>
            @endif
        </div>
    </form>
</div>
