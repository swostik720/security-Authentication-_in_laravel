<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
</head>
<body>
    <h1>Welcome User, {{ auth()->user()->name }}</h1>
    <p>This is the User Dashboard.</p>
    <!-- User-specific content here -->
    <form action="{{ url('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
    <button><a href="{{ route('profile') }}">Profile</a></button>
</body>
</html>
