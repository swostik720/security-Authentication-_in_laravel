<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        @if ($errors->any())
            <p style="color: red;">{{ $errors->first() }}</p>
        @endif
        <form action="{{ url('register') }}" method="POST">
            @csrf
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}">
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}">
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
            </div>
            <div>
                <label for="password_confirmation">Confirm Password:</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
            </div>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="{{ url('login') }}">Login here</a>.</p>

        <!-- Google Register Button -->
        <div>
            <a href="{{ url('auth/google') }}">
                <button type="button" style="background-color: #4285F4; color: white; border: none; padding: 10px 15px; cursor: pointer;">
                    Register with Google
                </button>
            </a>
        </div>
    </div>
</body>
</html>
