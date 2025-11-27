@extends('layouts.public')

@section('title', 'Check Application Status - EDUgate')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">Check Application Status</h1>
                <p class="text-lg text-gray-600">
                    Enter your details to check the status of your enrollment application
                </p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Status Check Form -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <form method="POST" action="{{ route('check-status.process') }}">
                    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label for="application_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Application ID
                            </label>
                            <input type="text" 
                                   id="application_id" 
                                   name="application_id" 
                                   placeholder="Enter your application ID (e.g., APP202400001)"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   value="{{ old('application_id') }}">
                            @error('application_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>  
                        <div>
                            <button type="submit" 
                                    class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition duration-300">
                                Check Status
                            </button>
                        </div>
                    </div>
                </form>
            </div>

			{{-- Results directly under the form for better UX --}}
			@if(isset($application))
				@php
					$fullName = trim(($application->first_name ?? $application->firstname ?? '') . ' ' . ($application->mi ?? '') . ' ' . ($application->last_name ?? $application->lastname ?? ''));
					$gradeShown = $application->grade_level ?? $application->grade ?? '—';
					$statusValue = $application->status ?? 'submitted';
					$isActive = in_array(strtolower($statusValue), ['active','enrolled','accepted']);
				@endphp
				<div class="bg-white rounded-lg shadow-lg p-6 mb-8">
					<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
						<div>
							<h2 class="text-xl font-semibold text-gray-800">{{ $fullName ?: 'Applicant' }}</h2>
							<p class="text-gray-600">Application ID: {{ $application->application_id }}</p>
						</div>
						<div class="flex items-center gap-4">
							<div class="text-sm">
								<p class="text-gray-500">Grade</p>
								<p class="font-medium text-gray-800">{{ $gradeShown }}</p>
							</div>
							<span class="px-3 py-1 rounded-full text-sm font-medium {{ $isActive ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
								{{ $isActive ? 'Active' : 'Not Active' }}
							</span>
						</div>
					</div>

					<hr class="my-4">

					<h3 class="text-lg font-semibold text-gray-800 mb-4">Application Status</h3>
					<div class="flex items-center mb-2">
						@switch($application->status)
							@case('submitted')
								<div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
									<svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
										<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
									</svg>
								</div>
								<div>
									<p class="font-semibold text-blue-800">Application Submitted</p>
									<p class="text-sm text-gray-600">Your application is in our system and will be reviewed soon.</p>
								</div>
								@break
							@case('under_review')
								<div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
									<svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
										<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
									</svg>
								</div>
								<div>
									<p class="font-semibold text-yellow-800">Under Review</p>
									<p class="text-sm text-gray-600">Our admissions team is currently reviewing your application.</p>
								</div>
								@break
							@case('interview_scheduled')
								<div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
									<svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
										<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
									</svg>
								</div>
								<div>
									<p class="font-semibold text-purple-800">Interview Scheduled</p>
									<p class="text-sm text-gray-600">Please check your email for interview details and instructions.</p>
								</div>
								@break
							@case('accepted')
								<div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
									<svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
										<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
									</svg>
								</div>
								<div>
									<p class="font-semibold text-green-800">Accepted</p>
									<p class="text-sm text-gray-600">Congratulations! Your application has been accepted. Welcome to our school!</p>
								</div>
								@break
							@case('rejected')
								<div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
									<svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
										<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
									</svg>
								</div>
								<div>
									<p class="font-semibold text-red-800">Not Accepted</p>
									<p class="text-sm text-gray-600">We appreciate your interest. You may reapply next academic year.</p>
								</div>
								@break
						@endswitch
					</div>

					@if(!empty($application->status_notes))
						<div class="mt-4 p-4 bg-gray-50 rounded-lg">
							<p class="text-sm text-gray-700"><strong>Additional Notes:</strong></p>
							<p class="text-sm text-gray-600 mt-1">{{ $application->status_notes }}</p>
						</div>
					@endif
				</div>
			@endif

            <!-- Information Cards -->
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 ml-4">Application Submitted</h3>
                    </div>
                    <p class="text-gray-600">Your application has been successfully received and is in our system.</p>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 ml-4">Under Review</h3>
                    </div>
                    <p class="text-gray-600">Our admissions team is currently reviewing your application and documents.</p>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.436L3 21l2.436-5.094A8.959 8.959 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 ml-4">Interview Scheduled</h3>
                    </div>
                    <p class="text-gray-600">An interview has been scheduled. Check your email for details.</p>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 ml-4">Accepted</h3>
                    </div>
                    <p class="text-gray-600">Congratulations! Your application has been accepted. Welcome to our school!</p>
                </div>
            </div>

            <!-- Help Section -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-800 mb-3">Need Help?</h3>
                <div class="text-blue-700 space-y-2">
                    <p><strong>Can't find your application ID?</strong> Check your email for the confirmation message sent after submission.</p>
                    <p><strong>Lost your confirmation email?</strong> Use your email and phone number to search for your application.</p>
                    <p><strong>Still having trouble?</strong> Contact our admissions office:</p>
                    <ul class="ml-4 mt-2 space-y-1">
                        <li>• Phone: (555) 123-4567</li>
                        <li>• Email: admissions@school.edu</li>
                        <li>• Office Hours: Monday - Friday, 9:00 AM - 5:00 PM</li>
                    </ul>
                </div>
            </div>


            @if(isset($error))
                <div class="mt-8 bg-red-50 border border-red-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <p class="text-red-700 font-semibold">{{ $error }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
