<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}"> <!-- Link to the external CSS file -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/panseat_logo.png') }}">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');

            form.addEventListener('submit', function(event) {
                // Trim whitespace from input fields
                const nameInput = document.getElementById('name');
                const emailInput = document.getElementById('email');
                const passwordInput = document.getElementById('password');

                nameInput.value = nameInput.value.trim();
                emailInput.value = emailInput.value.trim();
                passwordInput.value = passwordInput.value.trim();

                // Check for spaces in the password
                if (/\s/.test(passwordInput.value)) {
                    alert('Password cannot contain spaces.');
                    event.preventDefault(); // Prevent form submission
                    return;
                }
            });
        });
    </script>
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
            <h1 class="login-title">Register</h1>
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
            <form method="POST" action="{{ route('register2') }}">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="position">Role</label>
                    <select id="position" name="position" required>
                        <option value="" disabled selected>Select your role</option>
                        <option value="staff">Staff</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="login-button">Register</button>
            </form>
            <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
        </div>
    </div>
</body>
</html>