<!-- Account Settings Content -->
<div style="padding: 0;">
    <!-- Basic Account Information -->
    <div class="mb-4">
        <div class="section-card">
            <div class="section-header">
                <h3 class="section-title">
                    <i class="fas fa-user"></i>
                    Basic Account Information
                </h3>
            </div>
            <div class="section-body">
                <form id="accountForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="attio-label">Full Name</label>
                            <input type="text" class="attio-input" id="name" name="name" value="{{ $user->name }}" required>
                            <div class="invalid-feedback" style="font-size: 0.8125rem; color: #dc3545; margin-top: 0.25rem;"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="user_id" class="attio-label">Username/ID</label>
                            <input type="text" class="attio-input" id="user_id" name="user_id" value="{{ $user->user_id }}">
                            <small style="color: #666; font-size: 0.8125rem; margin-top: 0.25rem; display: block;">This will be used for login</small>
                            <div class="invalid-feedback" style="font-size: 0.8125rem; color: #dc3545; margin-top: 0.25rem;"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="attio-label">Email Address</label>
                        <input type="email" class="attio-input" id="email" name="email" value="{{ $user->email }}" required>
                        <div class="invalid-feedback" style="font-size: 0.8125rem; color: #dc3545; margin-top: 0.25rem;"></div>
                        @if(!$user->email_verified_at)
                            <small style="color: #8b6914; font-size: 0.8125rem; margin-top: 0.5rem; display: flex; align-items: center; gap: 0.25rem;">
                                <i class="fas fa-exclamation-triangle"></i>Email not verified
                            </small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="attio-label">Account Role</label>
                        <input type="text" class="attio-input" value="{{ ucfirst($user->role) }}" readonly style="background: #f5f5f5; cursor: not-allowed;">
                        <small style="color: #666; font-size: 0.8125rem; margin-top: 0.25rem; display: block;">Role cannot be changed</small>
                    </div>

                    <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
                        <button type="submit" class="action-btn-primary" id="updateAccountBtn">
                            <i class="fas fa-save"></i>
                            <span>Update Account</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Password Settings -->
    <div class="mb-4">
        <div class="section-card">
            <div class="section-header">
                <h3 class="section-title">
                    <i class="fas fa-lock"></i>
                    Password Settings
                </h3>
            </div>
            <div class="section-body">
                <form id="passwordForm">
                    @csrf
                    <div class="mb-3">
                        <label for="current_password" class="attio-label">Current Password</label>
                        <div style="display: flex; gap: 0.5rem;">
                            <input type="password" class="attio-input" id="current_password" name="current_password" required style="flex: 1;">
                            <button class="action-btn-secondary" type="button" onclick="togglePassword('current_password')" style="padding: 0.625rem 0.875rem;">
                                <i class="fas fa-eye" id="current_password_icon"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback" style="font-size: 0.8125rem; color: #dc3545; margin-top: 0.25rem;"></div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="attio-label">New Password</label>
                        <div style="display: flex; gap: 0.5rem;">
                            <input type="password" class="attio-input" id="password" name="password" required style="flex: 1;">
                            <button class="action-btn-secondary" type="button" onclick="togglePassword('password')" style="padding: 0.625rem 0.875rem;">
                                <i class="fas fa-eye" id="password_icon"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback" style="font-size: 0.8125rem; color: #dc3545; margin-top: 0.25rem;"></div>
                        <small style="color: #666; font-size: 0.8125rem; margin-top: 0.25rem; display: block;">Minimum 8 characters</small>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="attio-label">Confirm New Password</label>
                        <div style="display: flex; gap: 0.5rem;">
                            <input type="password" class="attio-input" id="password_confirmation" name="password_confirmation" required style="flex: 1;">
                            <button class="action-btn-secondary" type="button" onclick="togglePassword('password_confirmation')" style="padding: 0.625rem 0.875rem;">
                                <i class="fas fa-eye" id="password_confirmation_icon"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback" style="font-size: 0.8125rem; color: #dc3545; margin-top: 0.25rem;"></div>
                    </div>

                    <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
                        <button type="submit" class="action-btn-primary" id="updatePasswordBtn">
                            <i class="fas fa-key"></i>
                            <span>Update Password</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div>
        <div class="section-card" style="border-left: 3px solid #dc3545;">
            <div class="section-header">
                <h3 class="section-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Danger Zone
                </h3>
            </div>
            <div class="section-body">
                <p style="color: #666; font-size: 0.875rem; margin-bottom: 1rem;">Once you delete your account, there is no going back. Please be certain.</p>
                
                <button type="button" class="action-btn-primary btn-danger-outline" onclick="confirmAccountDeletion()">
                    <i class="fas fa-trash"></i>
                    <span>Delete Account</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Account Deletion Confirmation Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content attio-modal">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Account Deletion</h5>
                <button type="button" class="modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div style="padding: 1rem; background: #fff5f5; border: 1px solid #dc3545; border-radius: 8px; margin-bottom: 1rem;">
                    <strong style="color: #dc3545; font-size: 0.875rem;">Warning!</strong>
                    <span style="color: #dc3545; font-size: 0.875rem;"> This action cannot be undone.</span>
                </div>
                <p style="color: #666; font-size: 0.875rem; margin-bottom: 1rem;">Are you sure you want to delete your account? This will:</p>
                <ul style="color: #666; font-size: 0.875rem; padding-left: 1.5rem; margin-bottom: 1.5rem;">
                    <li>Permanently delete all your data</li>
                    <li>Remove access to all system features</li>
                    <li>Cancel any ongoing activities</li>
                </ul>
                
                <form id="deleteAccountForm">
                    @csrf
                    @method('DELETE')
                    <div class="mb-3">
                        <label for="delete_password" class="attio-label">Enter your password to confirm:</label>
                        <input type="password" class="attio-input" id="delete_password" name="password" required>
                        <div class="invalid-feedback" style="font-size: 0.8125rem; color: #dc3545; margin-top: 0.25rem;"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="action-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="action-btn-primary btn-danger-outline" onclick="deleteAccount()">
                    <i class="fas fa-trash"></i>
                    <span>Delete My Account</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Account form submission
    document.getElementById('accountForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('updateAccountBtn');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Updating...</span>';
        
        const formData = new FormData(this);
        
        fetch('{{ route("account.update") }}', {
            method: 'PATCH',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('success', data.message);
                clearFormErrors('accountForm');
                setTimeout(() => location.reload(), 1000);
            } else {
                if (data.errors) {
                    displayFormErrors('accountForm', data.errors);
                } else {
                    showNotification('error', data.message || 'Failed to update account');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'An error occurred while updating account');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    });

    // Password form submission
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('updatePasswordBtn');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Updating...</span>';
        
        const formData = new FormData(this);
        
        fetch('{{ route("account.password.update") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('success', data.message);
                clearFormErrors('passwordForm');
                this.reset();
            } else {
                if (data.errors) {
                    displayFormErrors('passwordForm', data.errors);
                } else {
                    showNotification('error', data.message || 'Failed to update password');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'An error occurred while updating password');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    });
});

function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '_icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function confirmAccountDeletion() {
    const modal = new bootstrap.Modal(document.getElementById('deleteAccountModal'));
    modal.show();
}

function deleteAccount() {
    const form = document.getElementById('deleteAccountForm');
    const formData = new FormData(form);
    
    fetch('{{ route("account.destroy") }}', {
        method: 'DELETE',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.ok) {
            showNotification('success', 'Account deleted successfully');
            setTimeout(() => window.location.href = '/', 2000);
        } else {
            return response.json().then(data => {
                if (data.errors) {
                    displayFormErrors('deleteAccountForm', data.errors);
                } else {
                    showNotification('error', 'Failed to delete account');
                }
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'An error occurred while deleting account');
    });
}

function displayFormErrors(formId, errors) {
    const form = document.getElementById(formId);
    clearFormErrors(formId);
    
    for (const [field, messages] of Object.entries(errors)) {
        const input = form.querySelector(`[name="${field}"]`);
        if (input) {
            input.classList.add('is-invalid');
            const feedback = input.nextElementSibling || input.parentNode.querySelector('.invalid-feedback');
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.textContent = messages[0];
                feedback.style.display = 'block';
            }
        }
    }
}

function clearFormErrors(formId) {
    const form = document.getElementById(formId);
    form.querySelectorAll('.is-invalid').forEach(el => {
        el.classList.remove('is-invalid');
    });
    form.querySelectorAll('.invalid-feedback').forEach(el => {
        el.textContent = '';
        el.style.display = 'none';
    });
}

function showNotification(type, message) {
    const notification = document.createElement('div');
    notification.className = 'alert alert-secondary position-fixed';
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; padding: 1rem 1.25rem; border-radius: 8px; border: 1px solid #e5e5e5; background: #ffffff; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); display: flex; justify-content: space-between; align-items: center;';
    notification.innerHTML = `
        <span style="color: ${type === 'success' ? '#1a1a1a' : '#dc3545'}; font-size: 0.875rem;">${message}</span>
        <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #666; cursor: pointer; padding: 0.25rem; margin-left: 1rem;">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}
</script>

<style>
.section-card {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 1.5rem;
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

.btn-danger-outline {
    border-color: #dc3545 !important;
    color: #dc3545 !important;
}

.btn-danger-outline:hover {
    background: #dc3545 !important;
    color: #ffffff !important;
    border-color: #dc3545 !important;
}

.attio-modal {
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

.attio-modal .modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #f0f0f0;
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
}
</style>
