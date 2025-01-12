<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:4|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('/login');
    }

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

        if (Auth::attempt($request->only('email', 'password'))) {
            // Check if the user is an admin or a regular user
            if (auth()->user()->is_admin) {
                return redirect('/admin/dashboard'); // Redirect to admin dashboard
            }

            return redirect('/user/dashboard'); // Redirect to user dashboard
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function redirectToGoogle()
    {
        // Redirect to Google's OAuth page
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // Retrieve the user information from Google
            $googleUser = Socialite::driver('google')->user();

            // Check if the user already exists in the database
            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'password' => Hash::make(Str::random(16)), // Generate a random password
                ]
            );

            // Log the user into the application
            Auth::login($user);

            // Redirect based on user role
            if ($user->is_admin) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('user.dashboard');
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Google Authentication Error: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->route('login')->with('error', 'Failed to authenticate with Google.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendPasswordResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', 'Password reset link sent!')
            : back()->withErrors(['email' => 'We couldn\'t find a user with that email address.']);
    }


    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:4',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect('/login')->with('status', 'Password reset successful.')
            : back()->withErrors(['email' => __($status)]);
    }
}
