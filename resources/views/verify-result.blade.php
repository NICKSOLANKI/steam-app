<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verified</title>
    <script>
        // Redirect to notlogin.index after 5 seconds
        setTimeout(() => {
            window.location.href = "{{ $redirect_url }}";
        }, 5000);
    </script>
</head>
<body>
    <h1>{{ $status }}</h1>
    <p>Your verification token: <strong>{{ $token }}</strong></p>
    <p>You will be redirected shortly...</p>
</body>
</html>
