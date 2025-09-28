<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Owner Dashboard - EDUgate')</title>
    
    <!-- Icon Libraries -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Owner CSS -->
    <link href="{{ asset('css/admin/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin/settings.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body class="{{ auth()->user()->theme ?? 'light' }}">
    
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('owner.dashboard') }}">
                <i class="fas fa-crown me-2"></i>
                EDUgate Owner
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}" href="{{ route('owner.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-building me-1"></i>Schools
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-chart-bar me-1"></i>Analytics
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-cogs me-1"></i>System Settings
                        </a>
                    </li>
                </ul>
                
                <!-- Profile Dropdown -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 14px; font-weight: bold;">
                                {{ strtoupper(substr(auth()->user()->name ?: 'O', 0, 1)) }}
                            </div>
                            {{ auth()->user()->name ?: 'Owner' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <h6 class="dropdown-header">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px; font-size: 16px;">
                                            {{ strtoupper(substr(auth()->user()->name ?: 'O', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ auth()->user()->name ?: 'Owner' }}</div>
                                            <small class="text-muted">{{ auth()->user()->email }}</small>
                                            <small class="text-muted d-block">System Owner</small>
                                        </div>
                                    </div>
                                </h6>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user me-2"></i>My Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="openAccountSettings()">
                                    <i class="fas fa-cog me-2"></i>Account Settings
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" onclick="confirmLogout()">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="container-fluid py-4">
        @yield('content')
    </main>
    
    <!-- Logout Form (Hidden) -->
    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin/script.js') }}"></script>
    
    <script>
        function confirmLogout() {
            if (confirm('Are you sure you want to logout?')) {
                document.getElementById('logoutForm').submit();
            }
        }
        
        function openAccountSettings() {
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
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
            
            // Show the modal using Bootstrap 5
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
            
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
            modal.addEventListener('hidden.bs.modal', function () {
                document.body.removeChild(modal);
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>
