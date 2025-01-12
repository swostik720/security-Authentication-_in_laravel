<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        @if ($errors->any())
            <p style="color: red;">{{ $errors->first() }}</p>
        @endif
        <form action="{{ url('login') }}" method="POST">
            @csrf
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}">
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
            </div>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="{{ url('register') }}">Register here</a>.</p>
        <p><a href="{{ url('forgot-password') }}">Forgot Password?</a></p>

        <!-- Google Login Button -->
        <div>
            <a href="{{ url('auth/google') }}">
                <button type="button" style="background-color: #4285F4; color: white; border: none; padding: 10px 15px; cursor: pointer;">
                    Login with Google
                </button>
            </a>
        </div>
    </div>
</body>
</html>
