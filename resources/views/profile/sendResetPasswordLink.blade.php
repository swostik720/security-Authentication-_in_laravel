<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Password Reset Link</title>
</head>
<body>
    <div class="container">
        <h2>Send Password Reset Link</h2>
        <p>To reset your password, enter your email address below and we'll send you a password reset link.</p>

        @if (session('status'))
            <p style="color: green;">{{ session('status') }}</p>
        @endif

        @if ($errors->any())
            <p style="color: red;">{{ $errors->first() }}</p>
        @endif

        <!-- Form to send reset password link -->
        <form action="{{ url('/profile/resetPasswordLink') }}" method="POST">
            @csrf
            <div>
                <label for="email">Enter your email address:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            <button type="submit">Send Password Reset Link</button>
        </form>

        <p>Remembered your password? <a href="{{ url('profile') }}">Go back to your profile</a>.</p>
    </div>
</body>
</html>
