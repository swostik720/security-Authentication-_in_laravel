<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;

class ProfileController extends Controller
{
    public function showProfile()
    {
        return view('profile.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Check if the user is authorized to update this profile
        $this->authorize('update', $user);  // Check if the user is allowed to update their profile

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('profile')->with('status', 'Profile updated successfully!');
    }


    public function showResetPasswordLinkForm()
    {
        return view('profile.sendResetPasswordLink');
    }

    public function resetPasswordLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', 'Password reset link sent!')
            : back()->withErrors(['email' => 'We couldn\'t find a user with that email address.']);
    }

    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        // Delete user from the database
        $user->delete();

        // Log the user out after deleting the account
        Auth::logout();

        // Redirect to login page
        return redirect('/login')->with('status', 'Your account has been deleted.');
    }
}
