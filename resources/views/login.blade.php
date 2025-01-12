<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}"> <!-- Link to the external CSS file -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Include Font Awesome -->
</head>
<body>
<h1 class="title">PansEat Tagapo</h1>
    <div class="login-container">
        <div class="login-logo">
            <div class="logo-outer">
                <div class="logo-inner">
                    <div class="logo-center"></div>
                </div>
            </div>
        </div>
        <div class="login-form-container">
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="error-messages">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="error-message">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

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
                <button type="submit" class ="login-button">Login</button>
            </form>
            <p class = "transfer">Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
        </div>
    </div>
</body>
</html>