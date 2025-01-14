<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
</head>
<body>
    <div class="container">
        <h1>Welcome, {{ auth()->user()->name ?? 'Guest' }}</h1>
        @auth
            <p>You are logged in as {{ auth()->user()->email }}.</p>
            <form action="{{ url('logout') }}" method="POST">
                @csrf
                <button type="submit">Logout</button>
            </form>
        @else
            <p><a href="{{ url('login') }}">Login</a> or <a href="{{ url('register') }}">Register</a> to access the system.</p>
        @endauth
    </div>
</body>
</html>
