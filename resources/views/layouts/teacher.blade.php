<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Teacher Portal - EDUgate')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Libraries -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    <!-- Teacher CSS -->
    <link href="{{ asset('css/shared/style.css') }}" rel="stylesheet">
    
    <style>
        /* Override default fonts */
        body, .navbar, .card, .btn, .form-control, .form-label {
            font-family: 'Inter', 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        }
        
        /* Enhanced navbar styling */
        .navbar-brand {
            font-weight: 600;
            font-size: 1.25rem;
        }
        
        .navbar-nav .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 6px;
            margin: 0 4px;
            padding: 8px 12px !important;
        }
        
        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-1px);
        }
        
        .navbar-nav .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: 600;
        }
        
        /* Card improvements */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }
        
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600;
        }
        
        /* Button improvements */
        .btn {
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn:hover {
            transform: translateY(-1px);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        
        .btn-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        /* Badge improvements */
        .badge {
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        
        /* Icon improvements */
        .fas, .far, .fab {
            font-weight: 600;
        }
        
        /* Typography improvements */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
            color: #2d3748;
        }
        
        .text-muted {
            color: #718096 !important;
        }
        
        /* Main content spacing */
        main {
            background-color: #f7fafc;
            min-height: calc(100vh - 76px);
        }
        
        /* Border colors for cards */
        .border-left-primary {
            border-left: 4px solid #667eea !important;
        }
        
        .border-left-success {
            border-left: 4px solid #38ef7d !important;
        }
        
        .border-left-info {
            border-left: 4px solid #667eea !important;
        }
        
        .border-left-warning {
            border-left: 4px solid #f093fb !important;
        }
        
        /* Responsive improvements */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.1rem;
            }
            
            main {
                padding: 1rem !important;
            }
        }
    </style>
     <link rel="icon" type="image/x-icon" href="{{ asset('images/darkie.png') }}">
    @stack('styles')
</head>
<body class="{{ auth()->user()->theme ?? 'light' }}">
    
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('teacher.dashboard') }}">
                <i class="fas fa-chalkboard-teacher me-2"></i>
                EDUgate Teacher
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}" href="{{ route('teacher.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('teacher.students.*') ? 'active' : '' }}" href="{{ route('teacher.students.index') }}">
                            <i class="fas fa-users me-1"></i>My Students
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('teacher.subjects.*') ? 'active' : '' }}" href="{{ route('teacher.subjects.index') }}">
                            <i class="fas fa-book me-1"></i>My Subjects
                        </a>
                    </li>
                </ul>
                
                <!-- Profile Dropdown -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2 flex-shrink-0" style="width: 32px; height: 32px; font-size: 12px; font-weight: 600;">
                                {{ auth()->user()->initials }}
                            </div>
                            {{ auth()->user()->name ?: 'Teacher' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <h6 class="dropdown-header">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2 flex-shrink-0" style="width: 40px; height: 40px; font-size: 14px; font-weight: 600;">
                                            {{ auth()->user()->initials }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ auth()->user()->name ?: 'Teacher' }}</div>
                                            <small class="text-muted">{{ auth()->user()->email }}</small>
                                            @if(auth()->user()->teacher && auth()->user()->teacher->department)
                                                <small class="text-muted d-block">{{ auth()->user()->teacher->department }}</small>
                                            @endif
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
