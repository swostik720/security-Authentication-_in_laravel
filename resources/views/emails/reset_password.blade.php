<p>Hello {{ $name }},</p>

<p>We received a request to reset your password. Click the button below to reset it:</p>

<p><a href="{{ $resetUrl }}">Reset Password</a></p>

<p>If you did not request a password reset, no further action is required.</p>

<p>Thanks,<br>{{ config('app.name') }}</p>
