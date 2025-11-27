@extends('layouts.public')

@section('title', 'Student Enrollment - EDUgate')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">Student Enrollment</h1>
                <p class="text-lg text-gray-600">
                    Join our school community by completing the enrollment form below
                </p>
            </div>

            <!-- Enrollment Form -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                @if($errors->any())
                    <div class="mb-6 rounded border border-red-200 bg-red-50 p-4 text-red-700">
                        <p class="font-semibold">There was a problem submitting your application:</p>
                        <ul class="mt-2 list-disc pl-5 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('enrollment.submit') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Personal Information -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Personal Information</h2>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="firstname" class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                                <input type="text" id="firstname" name="firstname" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('firstname') }}">
                            </div>
                            <div>
                                <label for="lastname" class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                                <input type="text" id="lastname" name="lastname" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('lastname') }}">
                            </div>
                        </div>

                        <div class="grid md:grid-cols-3 gap-6 mt-6">
                            <div>
                                <label for="mi" class="block text-sm font-medium text-gray-700 mb-2">Middle Initial</label>
                                <input type="text" id="mi" name="mi" maxlength="10"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('mi') }}">
                            </div>
                            <div>
                                <label for="sex" class="block text-sm font-medium text-gray-700 mb-2">Sex *</label>
                                <select id="sex" name="sex" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select Sex</option>
                                    <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div>
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth *</label>
                                <input type="date" id="date_of_birth" name="date_of_birth" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('date_of_birth') }}">
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="religion" class="block text-sm font-medium text-gray-700 mb-2">Religion *</label>
                                <input type="text" id="religion" name="religion" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('religion') }}">
                            </div>
                            <div>
                                <label for="lrn_number" class="block text-sm font-medium text-gray-700 mb-2">LRN Number (optional)</label>
                                <input type="text" id="lrn_number" name="lrn_number"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('lrn_number') }}" placeholder="12-digit LRN">
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="current_address" class="block text-sm font-medium text-gray-700 mb-2">Current Address *</label>
                            <textarea id="current_address" name="current_address" rows="3" required 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Enter your complete address">{{ old('current_address') }}</textarea>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Contact Information</h2>
                        <div class="grid md:grid-cols-3 gap-6">
                            <div class="md:col-span-1">
                                <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                                <input type="tel" id="contact_number" name="contact_number" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('contact_number') }}">
                            </div>
                            <div class="md:col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" id="email" name="email" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('email') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Portal Account (optional password) -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Portal Account</h2>
                        <p class="text-sm text-gray-600 mb-4">You can set a password now or leave it blank and we'll email you a secure link to set your password later.</p>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                <input type="password" id="password" name="password"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       autocomplete="new-password">
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       autocomplete="new-password">
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Academic Information</h2>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="grade" class="block text-sm font-medium text-gray-700 mb-2">Grade Level *</label>
                                <select id="grade" name="grade" required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select Grade</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('grade') == $i ? 'selected' : '' }}>Grade {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label for="school_year" class="block text-sm font-medium text-gray-700 mb-2">School Year</label>
                                <input type="text" id="school_year" name="school_year" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('school_year') }}" placeholder="e.g., 2025-2026">
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="previous_school" class="block text-sm font-medium text-gray-700 mb-2">Previous School</label>
                            <input type="text" id="previous_school" name="previous_school"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   value="{{ old('previous_school') }}" placeholder="Name of previous school">
                        </div>
                    </div>

                    <!-- Special Needs Information -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Special Needs Information</h2>
                        <div class="flex items-start">
                            <input type="hidden" name="pwd" value="0">
                            <input type="checkbox" id="pwd" name="pwd" value="1" 
                                   class="mt-1 mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                   {{ old('pwd') ? 'checked' : '' }}>
                            <label for="pwd" class="text-sm text-gray-700">Student is a Person with Disability (PWD)</label>
                        </div>
                        <div class="mt-3">
                            <label for="pwd_details" class="block text-sm font-medium text-gray-700 mb-2">PWD Details</label>
                            <textarea id="pwd_details" name="pwd_details" rows="2"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Please specify details if applicable">{{ old('pwd_details') }}</textarea>
                        </div>
                    </div>

                    <!-- Parent/Guardian Information -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Parent/Guardian Information</h2>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="parent_name" class="block text-sm font-medium text-gray-700 mb-2">Parent/Guardian Name *</label>
                                <input type="text" id="parent_name" name="parent_name" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('parent_name') }}">
                            </div>
                            <div>
                                <label for="relationship" class="block text-sm font-medium text-gray-700 mb-2">Relationship *</label>
                                <select id="relationship" name="relationship" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select Relationship</option>
                                    <option value="father" {{ old('relationship')=='father' ? 'selected' : '' }}>Father</option>
                                    <option value="mother" {{ old('relationship')=='mother' ? 'selected' : '' }}>Mother</option>
                                    <option value="guardian" {{ old('relationship')=='guardian' ? 'selected' : '' }}>Guardian</option>
                                    <option value="other" {{ old('relationship')=='other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="parent_phone" class="block text-sm font-medium text-gray-700 mb-2">Parent/Guardian Phone *</label>
                                <input type="tel" id="parent_phone" name="parent_phone" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('parent_phone') }}">
                            </div>
                            <div>
                                <label for="parent_email" class="block text-sm font-medium text-gray-700 mb-2">Parent/Guardian Email</label>
                                <input type="email" id="parent_email" name="parent_email"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('parent_email') }}">
                            </div>
                        </div>
                        <div class="grid md:grid-cols-3 gap-6">
                            <div>
                                <label for="father_firstname" class="block text-sm font-medium text-gray-700 mb-2">Father First Name</label>
                                <input type="text" id="father_firstname" name="father_firstname"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('father_firstname') }}">
                            </div>
                            <div>
                                <label for="father_mi" class="block text-sm font-medium text-gray-700 mb-2">Father MI</label>
                                <input type="text" id="father_mi" name="father_mi" maxlength="10"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('father_mi') }}">
                            </div>
                            <div>
                                <label for="father_lastname" class="block text-sm font-medium text-gray-700 mb-2">Father Last Name</label>
                                <input type="text" id="father_lastname" name="father_lastname"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('father_lastname') }}">
                            </div>
                        </div>

                        <div class="grid md:grid-cols-3 gap-6 mt-6">
                            <div>
                                <label for="mother_firstname" class="block text-sm font-medium text-gray-700 mb-2">Mother First Name</label>
                                <input type="text" id="mother_firstname" name="mother_firstname"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('mother_firstname') }}">
                            </div>
                            <div>
                                <label for="mother_mi" class="block text-sm font-medium text-gray-700 mb-2">Mother MI</label>
                                <input type="text" id="mother_mi" name="mother_mi" maxlength="10"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('mother_mi') }}">
                            </div>
                            <div>
                                <label for="mother_lastname" class="block text-sm font-medium text-gray-700 mb-2">Mother Last Name</label>
                                <input type="text" id="mother_lastname" name="mother_lastname"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('mother_lastname') }}">
                            </div>
                        </div>

                        <div class="grid md:grid-cols-3 gap-6 mt-6">
                            <div>
                                <label for="guardian_firstname" class="block text-sm font-medium text-gray-700 mb-2">Guardian First Name</label>
                                <input type="text" id="guardian_firstname" name="guardian_firstname"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('guardian_firstname') }}">
                            </div>
                            <div>
                                <label for="guardian_mi" class="block text-sm font-medium text-gray-700 mb-2">Guardian MI</label>
                                <input type="text" id="guardian_mi" name="guardian_mi" maxlength="10"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('guardian_mi') }}">
                            </div>
                            <div>
                                <label for="guardian_lastname" class="block text-sm font-medium text-gray-700 mb-2">Guardian Last Name</label>
                                <input type="text" id="guardian_lastname" name="guardian_lastname"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('guardian_lastname') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Required Documents -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Required Documents</h2>
                        <p class="text-sm text-gray-600 mb-4">Upload the following documents now, or you can submit them later at the school. You may upload images (JPG/PNG) or PDF files up to 5 MB each.</p>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="birth_certificate" class="block text-sm font-medium text-gray-700 mb-2">Birth Certificate</label>
                                <input type="file" id="birth_certificate" name="birth_certificate" accept=".pdf,image/*"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                                <p class="text-xs text-gray-500 mt-2">Accepted: PDF, JPG, PNG. Max 5 MB.</p>
                            </div>
                            <div>
                                <label for="report_card" class="block text-sm font-medium text-gray-700 mb-2">Report Card / Form 138</label>
                                <input type="file" id="report_card" name="report_card" accept=".pdf,image/*"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                                <p class="text-xs text-gray-500 mt-2">Accepted: PDF, JPG, PNG. Max 5 MB.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Submit -->
                    <div class="mb-8">
                        <div class="flex items-start">
                            <input type="checkbox" id="terms" name="terms" required 
                                   class="mt-1 mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="terms" class="text-sm text-gray-700">
                                I agree to the Terms and Conditions and Privacy Policy of the school.
                            </label>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" 
                                class="bg-blue-600 text-white px-12 py-4 rounded-lg text-lg font-semibold hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition duration-300">
                            Submit Enrollment Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
