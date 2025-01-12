<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>

        @if (session('status'))
            <p style="color: green;">{{ session('status') }}</p>
        @endif

        @if ($errors->any())
            <p style="color: red;">{{ $errors->first() }}</p>
        @endif

        <form action="{{ url('forgot-password') }}" method="POST">
            @csrf
            <div>
                <label for="email">Enter your email address:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}">
            </div>
            <button type="submit">Send Password Reset Link</button>
        </form>

        <p>Remembered your password? <a href="{{ url('login') }}">Login here</a>.</p>
    </div>
</body>
</html>
