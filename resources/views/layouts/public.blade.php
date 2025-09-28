<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EDUgate - School Management System')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Icon Libraries -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    <!-- Your Custom CSS -->
    <link href="{{ asset('css/shared/style.css') }}" rel="stylesheet">
    
    <!-- Page-specific CSS -->
    @stack('styles')
    
    <link rel="icon" type="image/x-icon" href="{{ asset('images/darkie.png') }}">
</head>
<body class="bg-gray-50">
    @include('layouts.navbar')
    
    <main>
        @yield('content')
    </main>
    
    @include('layouts.footer')
    
    <!-- Alpine.js for interactivity -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Your Custom JS -->
    <script src="{{ asset('js/shared/app.js') }}"></script>
    
    @stack('scripts')
</body>
</html>