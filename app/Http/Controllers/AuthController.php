<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Registered;
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

        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Send the verification link
        event(new Registered($user));

        // Redirect with a status message
        return redirect('/register')->with('status', 'Registration successful. Please verify your email to activate your account.');
    }


    public function verifyEmail(Request $request, $id, $hash)
    {
        // Find the user by their ID
        $user = User::findOrFail($id);

        // Verify the hash by comparing it with the email hash
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect('/register')->withErrors(['email' => 'Invalid or expired verification link.']);
        }

        // If the user's email is not verified, mark it as verified
        if (!$user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();

            // Return success message after successful verification
            return redirect('/register')->with('status', 'Your email has been successfully verified! You can now log in.');
        }

        // If the email is already verified, proceed with login
        Auth::login($user);

        // Status message for login after successful verification
        return redirect('/login')->with('status', 'Email verified successfully! You can now log in.');
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

            if (!Auth::user()->email_verified_at === null) {
                Auth::logout(); // Log the user out if their email is not verified

                return redirect('/login')->withErrors(['email' => 'Please verify your email address to log in.']);
            }

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
