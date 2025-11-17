<?php
// routes/web.php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/enrollment', [HomeController::class, 'enrollment'])->name('enrollment');
Route::post('/enrollment', [HomeController::class, 'submitEnrollment'])
    ->middleware('throttle:5,1')
    ->name('enrollment.submit');
Route::get('/check-status', [HomeController::class, 'checkStatus'])->name('check-status');
Route::post('/check-status', [HomeController::class, 'processStatusCheck'])->name('check-status.process');
// Public staff registration disabled for deployment security
// Route::get('/register', [HomeController::class, 'showRegistration'])->name('register');
// Route::post('/register', [HomeController::class, 'processRegistration'])->name('register.process');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    
    // Password Reset Routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1')->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    
    // General dashboard route - redirects to role-specific dashboard
    Route::get('/dashboard', function () {
        $user = auth()->user();
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'teacher':
                return redirect()->route('teacher.dashboard');
            case 'student':
                return redirect()->route('student.dashboard');
            case 'owner':
                return redirect()->route('owner.dashboard');
            default:
                abort(403, 'Unauthorized access');
        }
    })->name('dashboard');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Account settings routes (for modal/overlay)
    Route::get('/account-settings', [ProfileController::class, 'accountSettings'])->name('account.settings');
    Route::patch('/account-settings', [ProfileController::class, 'updateAccount'])->name('account.update');
    Route::post('/account-settings/password', [ProfileController::class, 'updatePassword'])->name('account.password.update');
    Route::delete('/account', [ProfileController::class, 'destroy'])->name('account.destroy');
    
    // Dashboard stats API
    Route::get('/api/dashboard-stats', [DashboardController::class, 'getDashboardStats']);
    
    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
        
        // Students management
        Route::resource('students', StudentController::class);
        Route::patch('/students/{student}/status', [StudentController::class, 'updateStatus'])->name('students.update-status');
        
        // Teachers management
        Route::resource('teachers', TeacherController::class);
        Route::patch('/teachers/{teacher}/status', [TeacherController::class, 'updateStatus'])->name('teachers.update-status');
        Route::delete('/teachers/{teacher}/remove-picture', [TeacherController::class, 'removeProfilePicture'])
             ->name('teachers.remove-picture');
        
        // Subjects management
        Route::resource('subjects', SubjectController::class);
        
        // Files management
        Route::get('/files', [App\Http\Controllers\FilesController::class, 'index'])->name('files.index');
        Route::get('/files/student/{student}', [App\Http\Controllers\FilesController::class, 'show'])->name('files.show');
        Route::patch('/files/file/{file}/status', [App\Http\Controllers\FilesController::class, 'updateFileStatus'])->name('files.update-status');
        Route::patch('/files/student/{student}/status', [App\Http\Controllers\FilesController::class, 'updateStudentStatus'])->name('files.update-student-status');
        Route::get('/files/file/{file}/download', [App\Http\Controllers\FilesController::class, 'download'])->name('files.download');
        Route::get('/files/file/{file}/view', [App\Http\Controllers\FilesController::class, 'view'])->name('files.view');
        Route::delete('/files/file/{file}', [App\Http\Controllers\FilesController::class, 'destroy'])->name('files.destroy');
        Route::get('/files/stats', [App\Http\Controllers\FilesController::class, 'getStats'])->name('files.stats');
        
        // Theme update
        Route::post('/update-theme', function(\Illuminate\Http\Request $request) {
            $request->validate(['theme' => 'required|in:light,dark']);
            auth()->user()->update(['theme' => $request->theme]);
            return response()->json(['success' => true]);
        });
    });
    
    // Teacher routes
    Route::middleware('role:teacher')->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'teacherDashboard'])->name('dashboard');
        
        // Students management for teachers
        Route::resource('students', StudentController::class);
        
        // Teachers management for teachers (view only their colleagues)
        Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
        Route::get('/teachers/{teacher}', [TeacherController::class, 'show'])->name('teachers.show');
        
        // Subjects management
        Route::resource('subjects', SubjectController::class);
        
        // Teacher's own files management
        Route::get('/files', [App\Http\Controllers\TeacherFileController::class, 'index'])->name('files.index');
        
        
        
        Route::post('/files', [App\Http\Controllers\TeacherFileController::class, 'store'])->name('files.store');
        Route::get('/files/{file}/download', [App\Http\Controllers\TeacherFileController::class, 'download'])->name('files.download');
        Route::get('/files/{file}/view', [App\Http\Controllers\TeacherFileController::class, 'view'])->name('files.view');
        Route::patch('/files/{file}', [App\Http\Controllers\TeacherFileController::class, 'update'])->name('files.update');
        Route::delete('/files/{file}', [App\Http\Controllers\TeacherFileController::class, 'destroy'])->name('files.destroy');
        Route::get('/files-stats', [App\Http\Controllers\TeacherFileController::class, 'getStats'])->name('files.stats');
    });
    
    // Student routes (conditionally require email verification)
    $requireVerification = (bool) env('REQUIRE_EMAIL_VERIFICATION', app()->environment('production'));
    $studentMiddleware = ['role:student'];
    if ($requireVerification) {
        $studentMiddleware[] = 'verified';
    }
    Route::middleware($studentMiddleware)->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'studentDashboard'])->name('dashboard');
        Route::get('/grades', [DashboardController::class, 'studentGrades'])->name('grades');
        // JSON for student directory & stats (read-only)
        Route::get('/api/students', [StudentController::class, 'index'])->name('students.index');
        // Self-service: edit/update own enrollment/profile details
        Route::get('/enrollment/edit', [\App\Http\Controllers\StudentSelfController::class, 'edit'])
            ->name('enrollment.edit');
        Route::patch('/enrollment', [\App\Http\Controllers\StudentSelfController::class, 'update'])
            ->name('enrollment.update');
        // Self-service files management
        Route::get('/files', [\App\Http\Controllers\StudentFilesController::class, 'index'])->name('files.index');
        Route::post('/files', [\App\Http\Controllers\StudentFilesController::class, 'store'])->name('files.store');
        Route::patch('/files/{file}', [\App\Http\Controllers\StudentFilesController::class, 'update'])->name('files.update');
        Route::delete('/files/{file}', [\App\Http\Controllers\StudentFilesController::class, 'destroy'])->name('files.destroy');
        Route::get('/files/{file}/view', [\App\Http\Controllers\StudentFilesController::class, 'view'])->name('files.view');
    });
    
    // Owner routes
    Route::middleware('role:owner')->prefix('owner')->name('owner.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'ownerDashboard'])->name('dashboard');
        // Add more owner-specific routes here
    });
});

require __DIR__.'/auth.php';