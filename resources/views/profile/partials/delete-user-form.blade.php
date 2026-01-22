<div>
    <p style="color: #666; font-size: 0.875rem; margin-bottom: 1.5rem;">
        Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
    </p>

    <button type="button" class="action-btn-primary btn-danger-outline" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
        <i class="fas fa-trash"></i>
        <span>Delete Account</span>
    </button>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content attio-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountModalLabel">Delete Account</h5>
                    <button type="button" class="modal-close" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form method="post" action="{{ route('account.destroy') }}">
                    @csrf
                    @method('delete')
                    
                    <div class="modal-body">
                        <div style="padding: 1rem; background: #fff5f5; border: 1px solid #dc3545; border-radius: 8px; margin-bottom: 1rem;">
                            <strong style="color: #dc3545; font-size: 0.875rem;">Warning!</strong>
                            <span style="color: #dc3545; font-size: 0.875rem;"> This action cannot be undone.</span>
                        </div>
                        
                        <p style="color: #666; font-size: 0.875rem; margin-bottom: 1rem;">
                            Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted.
                        </p>

                        <div class="mb-3">
                            <label for="delete_password" class="attio-label">Please enter your password to confirm:</label>
                            <input type="password" 
                                   class="attio-input @error('password', 'userDeletion') is-invalid @enderror" 
                                   id="delete_password" 
                                   name="password" 
                                   placeholder="Enter your current password" 
                                   required>
                            @error('password', 'userDeletion')
                                <div class="invalid-feedback" style="font-size: 0.8125rem; color: #dc3545; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="action-btn-secondary" data-bs-dismiss="modal">
                            <span>Cancel</span>
                        </button>
                        <button type="submit" class="action-btn-primary btn-danger-outline">
                            <i class="fas fa-trash"></i>
                            <span>Delete Account</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
