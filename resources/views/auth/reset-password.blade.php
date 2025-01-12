<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <div class="container">
        <h2>Reset Your Password</h2>

        @if (session('status'))
            <p style="color: green;">{{ session('status') }}</p>
        @endif

        @if ($errors->any())
            <p style="color: red;">{{ $errors->first() }}</p>
        @endif
        
        <form action="{{ url('reset-password') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}">
            </div>

            <div>
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div>
                <label for="password_confirmation">Confirm Password:</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
            </div>

            <button type="submit">Reset Password</button>
        </form>

        <p>Remembered your password? <a href="{{ url('login') }}">Login here</a>.</p>
    </div>
</body>
</html>
