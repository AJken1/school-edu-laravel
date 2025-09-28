<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile page.
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        $profile = $user->profile(); // Get the specific profile (admin/teacher/student)
        
        return view('profile.show', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    /**
     * Display the account settings (for modal/overlay).
     */
    public function accountSettings(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'user' => $request->user()->only(['name', 'user_id', 'email', 'role'])
            ]);
        }
        
        return view('profile.account-settings', [
            'user' => $request->user(),
        ]);
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $profile = $user->profile();
        
        // Update basic user information
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Update profile-specific information based on role
        if ($profile && $request->has('profile_data')) {
            $profileData = $request->input('profile_data');
            
            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                // Delete old profile picture
                if ($profile->profile_picture && $profile->profile_picture !== 'user.png') {
                    Storage::delete('public/' . $profile->profile_picture);
                }
                
                $imageName = time() . '.' . $request->profile_picture->extension();
                $request->profile_picture->storeAs('public/profiles', $imageName);
                $profileData['profile_picture'] = 'profiles/' . $imageName;
            }
            
            $profile->update($profileData);
        }

        return Redirect::route('profile.show')->with('status', 'profile-updated');
    }

    /**
     * Update account settings (username, email, etc.)
     */
    public function updateAccount(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'user_id' => 'sometimes|string|max:255|unique:users,user_id,' . $request->user()->id,
            'email' => 'sometimes|email|max:255|unique:users,email,' . $request->user()->id,
        ]);

        $user = $request->user();
        $updateData = $request->only(['name', 'user_id', 'email']);
        
        if (isset($updateData['email']) && $updateData['email'] !== $user->email) {
            $updateData['email_verified_at'] = null;
        }

        $user->update($updateData);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Account updated successfully',
                'user' => $user->only(['name', 'user_id', 'email', 'role'])
            ]);
        }

        return back()->with('status', 'account-updated');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully'
            ]);
        }

        return back()->with('status', 'password-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}