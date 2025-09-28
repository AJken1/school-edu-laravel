<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();
            
            // Handle both AJAX and form submissions
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'redirect' => $this->getRedirectPath($user->role)
                ]);
            }
            
            return redirect()->intended($this->getRedirectPath($user->role));
        }

        // Handle both AJAX and form submissions
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email or password!'
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
    }

    private function getRedirectPath($role)
    {
        return match($role) {
            'admin' => '/admin/dashboard',
            'teacher' => '/teacher/dashboard',
            'student' => '/student/dashboard',
            'owner' => '/owner/dashboard',
            default => '/',
        };
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        return redirect()->route('home')->with('success', 'You have been successfully logged out.');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function sendResetCode(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users']);

        $otp = rand(100000, 999999);
        session(['otp' => $otp, 'reset_email' => $request->email]);

        // Send OTP via email (you'll need to configure mail settings)
        try {
            Mail::raw("Your password reset code is: {$otp}", function ($message) use ($request) {
                $message->to($request->email)->subject('Password Reset Code');
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Reset code sent to your email'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'success',
                'message' => "Reset code: {$otp}" // For testing purposes
            ]);
        }
    }

    public function verifyResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric'
        ]);

        if (session('otp') == $request->otp && session('reset_email') == $request->email) {
            return response()->json([
                'status' => 'success',
                'message' => 'OTP verified successfully'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid OTP!'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|min:3|confirmed'
        ]);

        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);

        session()->forget(['otp', 'reset_email']);

        return response()->json([
            'status' => 'success',
            'message' => 'Password updated successfully'
        ]);
    }
}