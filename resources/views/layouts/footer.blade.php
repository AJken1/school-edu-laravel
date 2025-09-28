<footer class="bg-gray-800 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-4 gap-8">
            <!-- School Info -->
            <div class="md:col-span-2">
                <div class="flex items-center mb-4">
                    <img src="{{ asset('images/darkie.png') }}" alt="EDUgate Logo" class="h-16 w-auto object-contain mr-3">
                    <h3 class="text-2xl font-bold">EDUgate</h3>
                </div>
                <p class="text-gray-300 mb-4">
                    Empowering students with quality education and fostering excellence in learning. 
                    Join our community of learners and educators dedicated to academic success.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition duration-300">Home</a></li>
                    <li><a href="{{ route('enrollment') }}" class="text-gray-300 hover:text-white transition duration-300">Enrollment</a></li>
                    <li><a href="{{ route('check-status') }}" class="text-gray-300 hover:text-white transition duration-300">Check Status</a></li>
                    <li><a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition duration-300">Login</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Contact Us</h4>
                <div class="space-y-3 text-gray-300">
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt mr-3"></i>
                        <span>123 Education Street<br>Learning City, LC 12345</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-phone mr-3"></i>
                        <span>(+63) 123-456-789</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-envelope mr-3"></i>
                        <span>info@edugate.edu</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-clock mr-3"></i>
                        <span>Mon - Fri: 8:00 AM - 5:00 PM</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-700 mt-8 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-300 text-sm">
                    &copy; {{ date('Y') }} EDUgate School Management System. All rights reserved.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-300 hover:text-white text-sm transition duration-300">Privacy Policy</a>
                    <a href="#" class="text-gray-300 hover:text-white text-sm transition duration-300">Terms of Service</a>
                    <a href="#" class="text-gray-300 hover:text-white text-sm transition duration-300">Contact</a>
                </div>
            </div>
        </div>
    </div>
</footer>
