<!-- Navbar -->
<nav class="navbar">
  <div class="navbar-content">
    <form action="#" method="GET" class="search-form">
        <div class="form-input">
            <input type="search" name="search" placeholder="Search...">
            <button type="submit" class="search-btn">
                <i class='bx bx-search'></i>
            </button>
        </div>
    </form>
    
    <div class="navbar-right">
        <!-- Profile Dropdown -->
        <div class="profile-dropdown">
            <div class="profile-trigger" onclick="toggleProfileDropdown()">
                <img src="{{ auth()->user()->admin?->profile_image ?? asset('images/user.png') }}" alt="Profile" class="profile-img">
                <span class="profile-name">{{ auth()->user()->name }}</span>
                <i class='bx bx-chevron-down'></i>
            </div>
            
            <!-- Dropdown Menu -->
            <div class="profile-menu" id="profileMenu">
                <div class="profile-header">
                    <img src="{{ auth()->user()->admin?->profile_image ?? asset('images/user.png') }}" alt="Profile">
                    <div class="profile-info">
                        <h4>{{ auth()->user()->name }}</h4>
                        <p>{{ ucfirst(auth()->user()->role) }}</p>
                        <small>{{ auth()->user()->email }}</small>
                    </div>
                </div>
                
                <div class="profile-links">
                    <a href="{{ route('profile.show') }}" class="profile-link">
                        <i class='bx bx-user'></i>
                        <span>My Profile</span>
                    </a>
                    <a href="#" class="profile-link" onclick="openAccountSettings()">
                        <i class='bx bx-cog'></i>
                        <span>Account Settings</span>
                    </a>
                    <a href="#" class="profile-link">
                        <i class='bx bx-help-circle'></i>
                        <span>Help</span>
                    </a>
                    <div class="profile-divider"></div>
                    <a href="#" class="profile-link logout-link" onclick="confirmLogout()">
                        <i class='bx bx-log-out'></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
  </div>
</nav>

<!-- Logout Form (Hidden) -->
<form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- Profile Dropdown Styles -->
<style>
.navbar {
    background: #fff;
    padding: 0 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.navbar-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 60px;
}

.navbar-right {
    display: flex;
    align-items: center;
    gap: 20px;
}


.profile-dropdown {
    position: relative;
}

.profile-trigger {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    border-radius: 25px;
    background: #f8f9fa;
    cursor: pointer;
    transition: all 0.3s ease;
}

.profile-trigger:hover {
    background: #e9ecef;
}

.profile-img {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}

.profile-name {
    font-weight: 500;
    color: #333;
    max-width: 120px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.profile-menu {
    position: absolute;
    top: calc(100% + 10px);
    right: 0;
    width: 280px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 1001;
}

.profile-menu.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 20px;
    border-bottom: 1px solid #f0f0f0;
}

.profile-header img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.profile-info h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #333;
}

.profile-info p {
    margin: 2px 0;
    font-size: 14px;
    color: #6c757d;
}

.profile-info small {
    font-size: 12px;
    color: #9ca3af;
}

.profile-links {
    padding: 10px 0;
}

.profile-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
}

.profile-link:hover {
    background: #f8f9fa;
    color: #0066cc;
}

.profile-link i {
    font-size: 18px;
    width: 20px;
}

.profile-divider {
    height: 1px;
    background: #f0f0f0;
    margin: 8px 0;
}

.logout-link {
    color: #dc3545 !important;
}

.logout-link:hover {
    background: #fee;
}

</style>

<script>
function openAccountSettings() {
    // Close the profile dropdown first
    const profileMenu = document.getElementById('profileMenu');
    if (profileMenu) {
        profileMenu.classList.remove('show');
    }
    
    // Create a temporary overlay modal for account settings
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'tempAccountSettingsModal';
    modal.setAttribute('tabindex', '-1');
    modal.setAttribute('role', 'dialog');
    modal.innerHTML = `
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
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin fa-2x text-muted mb-3"></i>
                        <p class="text-muted">Loading account settings...</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Show the modal using jQuery (Bootstrap 4)
    $('#tempAccountSettingsModal').modal('show');
    
    // Load account settings content
    fetch('{{ route("account.settings") }}')
        .then(response => response.text())
        .then(html => {
            modal.querySelector('.modal-body').innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading account settings:', error);
            modal.querySelector('.modal-body').innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle fa-2x text-warning mb-3"></i>
                    <p class="text-muted">Failed to load account settings. Please try again.</p>
                    <button class="btn btn-primary" onclick="location.reload()">Retry</button>
                </div>
            `;
        });
    
    // Clean up when modal is closed
    $('#tempAccountSettingsModal').on('hidden.bs.modal', function () {
        document.body.removeChild(modal);
    });
}
</script>