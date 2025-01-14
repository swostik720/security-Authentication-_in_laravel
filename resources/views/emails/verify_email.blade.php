<p>Hello {{ $name }},</p>

<p>Thank you for registering. Please click the button below to verify your email address:</p>

<p><a href="{{ $verificationUrl }}">Verify Email</a></p>

<p>If you did not create an account, no further action is required.</p>

<p>Thanks,<br>{{ config('app.name') }}</p>
