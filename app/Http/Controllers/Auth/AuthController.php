<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;

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
            ], 401);
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

    public function showResetPasswordForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email')
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Password has been reset successfully.'
                ]);
            }

            return redirect()->route('login')->with('status', __($status));
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'error',
                'message' => __($status)
            ], 422);
        }

        throw ValidationException::withMessages([
            'email' => [__($status)]
        ]);
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return $request->expectsJson()
                ? response()->json([
                    'status' => 'success',
                    'message' => __('passwords.sent')
                ])
                : back()->with('status', __($status));
        }

        // Always return a generic message to prevent user enumeration
        return $request->expectsJson()
            ? response()->json([
                'status' => 'success',
                'message' => __('passwords.sent')
            ])
            : back()->with('status', __('passwords.sent'));
    }
}