<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PansEat Tagapo</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="header">
            <div>
                <h2 class="header-title">PansEat Tagapo</h2> 
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">Home</a>
                <a href="#" class="nav-link">About</a>
            </div>
        </div>
    </header>

    <main>
        <h1 class="main-title">Welcome to PansEat Tagapo</h1>
        
        <div class="section">
            <div class="info-section">
                <img src="{{asset('images/8.png')}}" alt="Image 1">
                <div class="info-content">
                    <h3>Our Restaurant</h3>
                    <p>Experience the finest Filipino cuisine in Tagapo. Our restaurant offers a wide variety of traditional dishes made with the freshest ingredients.</p>
                </div>
            </div>
            <div class="info-section">
                <img src="{{asset('images/punay994.jpg')}}" alt="Image 2">
                <div class="info-content">
                    <h3>Our Specialties</h3>
                    <p>Discover our signature dishes prepared by expert chefs using time-honored recipes and cooking techniques.</p>
                </div>
            </div>
        </div>

        <div class="calendar-section">
            <!-- Add your calendar content here -->
        </div>
    </main>

    <footer>
        <p class="contacts">panseattagapo@gmail.com</p>
        <p class="contacts">09817396111</p>
    </footer>
</body>
</html>