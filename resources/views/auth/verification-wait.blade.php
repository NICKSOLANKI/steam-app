<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification Waiting</title>
</head>
<body>
    <h1>Hi! Please verify your email</h1>
    <p>We have sent a verification email to: <strong>{{ $email }}</strong></p>
    @if(session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif

    <p>Check your email and click the verification link to continue.</p>
</body>
</html>
