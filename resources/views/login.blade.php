<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}"> <!-- Link to the external CSS file -->
</head>
<body>
    <div class="login-container">
        <div class="login-logo">
            <div class="logo-outer">
                <div class="logo-inner">
                    <div class="logo-center"></div>
                </div>
            </div>
        </div>
        <div class="login-form-container">
            <h1 class="login-title">Admin Log in</h1>
            <form method="POST" action="{{ route('login.authenticate') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
        </div>
    </div>
</body>
</html>
