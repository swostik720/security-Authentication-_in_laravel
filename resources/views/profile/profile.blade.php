<div class="container">
    <h1>Your Profile</h1>

    <div class="card">
        <div class="card-header">Profile Information</div>
        <div class="card-body">
            @if (session('status'))
                <p style="color: green;">{{ session('status') }}</p>
            @endif

            @if ($errors->any())
                <p style="color: red;">{{ $errors->first() }}</p>
            @endif


            <form method="POST" action="{{ url('/profile/update') }}">
                @csrf
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" class="form-control"
                        value="{{ auth()->user()->name }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" class="form-control"
                        value="{{ auth()->user()->email }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
    </div>

    <br>

    <div class="card">
        <div class="card-header">Reset Password</div>
        <div class="card-body">
            <button><a href="{{ url('/profile/resetPasswordLink') }}">Reset Your Password</a></button>
        </div>
    </div>

    <br>

    <div class="card">
        <div class="card-header">Delete Account</div>
        <div class="card-body">
            <form method="POST" action="{{ url('/profile/delete-account') }}" onsubmit="return confirmDelete()">
                @csrf
                <button type="submit" class="btn btn-danger">Delete Your Account</button>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete your account? This action cannot be undone.');
    }
</script>
