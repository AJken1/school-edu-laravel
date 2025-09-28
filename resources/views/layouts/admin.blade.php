<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard')</title>
    
    <!-- Icon Libraries -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Admin CSS -->
    <link href="{{ asset('css/admin/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin/settings.css') }}" rel="stylesheet">
    <link href="{{ asset('css/date-picker.css') }}" rel="stylesheet">
    
    @stack('styles')
    <link rel="icon" type="image/x-icon" href="{{ asset('images/darkie.png') }}">
</head>
<body class="{{ auth()->user()->theme ?? 'light' }}">
    <style>
    /* Responsive logo adjustments */
    @media (max-width: 768px) {
        .sidebar .logo img {
            height: 60px !important;
        }
    }
    
    @media (max-width: 480px) {
        .sidebar .logo img {
            height: 50px !important;
        }
    }
    </style>
    @include('admin.partials.sidebar')
    
    <div class="content">
        @include('admin.partials.navbar')
        
        <main>
            @yield('content')
        </main>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin/script.js') }}"></script>
    
    <!-- Enhanced Admin Scripts -->
    <script>
        // Profile Dropdown Toggle
        function toggleProfileDropdown() {
            const menu = document.getElementById('profileMenu');
            menu.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.querySelector('.profile-dropdown');
            const menu = document.getElementById('profileMenu');
            
            if (!dropdown.contains(event.target)) {
                menu.classList.remove('show');
            }
        });

        // Logout Confirmation
        function confirmLogout() {
            const result = confirm('Are you sure you want to logout? You will be redirected to the main page.');
            if (result) {
                document.getElementById('logoutForm').submit();
            }
        }


        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
        });

    </script>
    
    @stack('scripts')
</body>
</html>