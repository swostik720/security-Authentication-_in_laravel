<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Welcome Admin, {{ auth()->user()->name }}</h1>
    <p>This is the Admin Dashboard.</p>
    <!-- Admin-specific content here -->
    <form action="{{ url('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
