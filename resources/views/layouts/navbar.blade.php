<nav class="bg-white shadow-lg border-b border-gray-200">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('images/whitemode.png') }}" alt="EDUgate Logo" class="h-16 w-auto md:h-20 object-contain">
                    <h1 class="text-2xl md:text-3xl font-bold text-blue-600">EDUgate</h1>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 font-medium transition duration-300">
                    Home
                </a>
                <a href="{{ route('enrollment') }}" class="text-gray-700 hover:text-blue-600 font-medium transition duration-300">
                    Enrollment
                </a>
                <a href="{{ route('check-status') }}" class="text-gray-700 hover:text-blue-600 font-medium transition duration-300">
                    Check Status
                </a>
                
                @auth
                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 font-medium transition duration-300">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Admin Dashboard
                                </a>
                            @elseif(Auth::user()->role === 'teacher')
                                <a href="{{ route('teacher.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Teacher Dashboard
                                </a>
                            @elseif(Auth::user()->role === 'student')
                                <a href="{{ route('student.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Student Dashboard
                                </a>
                            @elseif(Auth::user()->role === 'owner')
                                <a href="{{ route('owner.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Owner Dashboard
                                </a>
                            @endif
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Staff Registration link removed (public staff registration disabled) -->
                    <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition duration-300">
                        Login
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button x-data="{ open: false }" @click="open = !open" class="text-gray-700 hover:text-blue-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div class="md:hidden border-t border-gray-200" x-data="{ mobileOpen: false }">
            <button @click="mobileOpen = !mobileOpen" class="w-full text-left py-2 text-gray-700 font-medium">
                Menu
            </button>
            
            <div x-show="mobileOpen" x-transition class="py-2 space-y-2">
                <a href="{{ route('home') }}" class="block py-2 text-gray-700 hover:text-blue-600 transition duration-300">
                    Home
                </a>
                <a href="{{ route('enrollment') }}" class="block py-2 text-gray-700 hover:text-blue-600 transition duration-300">
                    Enrollment
                </a>
                <a href="{{ route('check-status') }}" class="block py-2 text-gray-700 hover:text-blue-600 transition duration-300">
                    Check Status
                </a>
                
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="block py-2 text-gray-700 hover:text-blue-600 transition duration-300">
                            Admin Dashboard
                        </a>
                    @elseif(Auth::user()->role === 'teacher')
                        <a href="{{ route('teacher.dashboard') }}" class="block py-2 text-gray-700 hover:text-blue-600 transition duration-300">
                            Teacher Dashboard
                        </a>
                    @elseif(Auth::user()->role === 'student')
                        <a href="{{ route('student.dashboard') }}" class="block py-2 text-gray-700 hover:text-blue-600 transition duration-300">
                            Student Dashboard
                        </a>
                    @elseif(Auth::user()->role === 'owner')
                        <a href="{{ route('owner.dashboard') }}" class="block py-2 text-gray-700 hover:text-blue-600 transition duration-300">
                            Owner Dashboard
                        </a>
                    @endif
                    
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full text-left py-2 text-gray-700 hover:text-blue-600 transition duration-300">
                            Logout
                        </button>
                    </form>
                @else
                    <!-- Staff Registration link removed (public staff registration disabled) -->
                    <a href="{{ route('login') }}" class="block py-2 bg-blue-600 text-white text-center rounded-lg font-medium hover:bg-blue-700 transition duration-300 mt-2">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>