<!-- Account Settings Content -->
<div class="row">
    <!-- Basic Account Information -->
    <div class="col-12 mb-4">
        <div class="card border-0">
            <div class="card-header bg-light">
                <h6 class="mb-0 text-primary">
                    <i class="fas fa-user me-2"></i>Basic Account Information
                </h6>
            </div>
            <div class="card-body">
                <form id="accountForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_id" class="form-label">Username/ID</label>
                                <input type="text" class="form-control" id="user_id" name="user_id" value="{{ $user->user_id }}">
                                <small class="text-muted">This will be used for login</small>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                        <div class="invalid-feedback"></div>
                        @if(!$user->email_verified_at)
                            <small class="text-warning">
                                <i class="fas fa-exclamation-triangle me-1"></i>Email not verified
                            </small>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label">Account Role</label>
                        <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" readonly>
                        <small class="text-muted">Role cannot be changed</small>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary" id="updateAccountBtn">
                            <i class="fas fa-save me-2"></i>Update Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Password Settings -->
    <div class="col-12 mb-4">
        <div class="card border-0">
            <div class="card-header bg-light">
                <h6 class="mb-0 text-warning">
                    <i class="fas fa-lock me-2"></i>Password Settings
                </h6>
            </div>
            <div class="card-body">
                <form id="passwordForm">
                    @csrf
                    <div class="form-group">
                        <label for="current_password" class="form-label">Current Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="invalid-feedback"></div>
                        <small class="text-muted">Minimum 8 characters</small>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-warning" id="updatePasswordBtn">
                            <i class="fas fa-key me-2"></i>Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="col-12">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Danger Zone
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Once you delete your account, there is no going back. Please be certain.</p>
                
                <button type="button" class="btn btn-outline-danger" onclick="confirmAccountDeletion()">
                    <i class="fas fa-trash me-2"></i>Delete Account
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Account Deletion Confirmation Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Account Deletion
                </h5>
                                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <strong>Warning!</strong> This action cannot be undone.
                </div>
                <p>Are you sure you want to delete your account? This will:</p>
                <ul>
                    <li>Permanently delete all your data</li>
                    <li>Remove access to all system features</li>
                    <li>Cancel any ongoing activities</li>
                </ul>
                
                <form id="deleteAccountForm">
                    @csrf
                    @method('DELETE')
                    <div class="form-group">
                        <label for="delete_password" class="form-label">Enter your password to confirm:</label>
                        <input type="password" class="form-control" id="delete_password" name="password" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="deleteAccount()">
                    <i class="fas fa-trash me-2"></i>Delete My Account
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
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
        
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
                // Update the profile page data if needed
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
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
        
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
                this.reset(); // Clear the form
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
    const icon = input.nextElementSibling.querySelector('i');
    
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
    $('#deleteAccountModal').modal('show');
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
            const feedback = input.parentNode.querySelector('.invalid-feedback') || 
                           input.parentNode.parentNode.querySelector('.invalid-feedback');
            if (feedback) {
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
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}
</script>
