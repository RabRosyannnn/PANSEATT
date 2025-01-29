<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PansEat Tagapo</title>
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/panseat_logo.png') }}">
</head>
<body>
    <header>
        <div class="header">
            <div>
                <h2 class="header-title">PansEat Tagapo</h2> 
            </div>
            <div class="nav-item">
            <a href="{{ route('home') }}" class="nav-link">Home</a>
            <a href="{{ route('about') }}" class="nav-link">About</a>
            </div>
        </div>
    </header>

    <div class="about-container">
    <!-- First Row: Image - Text -->
    <div class="content-row">
        <div class="image-container">
            <div class="card">
                <img src="{{ asset('images/building.jpg') }}" 
                     alt="PansEat Tagapo Restaurant Exterior">
                <div class="card-overlay">
                    <h3>Our Restaurant</h3>
                    <p>Visit our welcoming three-story dining destination</p>
                </div>
            </div>
        </div>
        <div class="text-container">
            <p>
                Welcome to PansEat Tagapo – the home of authentic, delicious Filipino pancit and home-cooked meals 
                in the heart of Santa Rosa, Laguna. Since our humble beginnings in the late 1950s, PansEat Tagapo 
                has been a go-to spot for locals and visitors craving the comforting flavors of traditional Filipino 
                cuisine. What started as a small eatery has now grown into a three-story dining destination where 
                family, friends, and food lovers gather to enjoy our famous dishes.
            </p>
        </div>
    </div>

    <!-- Second Row: Text - Image -->
    <div class="content-row reverse">
        <div class="image-container">
            <div class="card">
                <img src="{{ asset('images/bihon.jpg') }}" 
                     alt="Signature Pancit Bihon Dish">
                <div class="card-overlay">
                    <h3>Signature Dish</h3>
                    <p>Our famous Pancit Bihon made with love</p>
                </div>
            </div>
        </div>
        <div class="text-container">
            <p>
                At the heart of our menu is our iconic Pancit Bihon, prepared fresh daily with love and dedication 
                to preserving its time-honored recipe. From its perfectly cooked noodles to the balance of savory 
                flavors, each bite is a celebration of Filipino culinary heritage. But that's not all – we offer a 
                wide variety of dishes to suit every craving. From combo meals to sizzling plates and other Filipino 
                favorites, we bring you comfort food that feels like home.
            </p>
        </div>
    </div>

    <!-- Third Row: Full Width Image and Text -->
    <div class="full-width-section">
        <div class="full-width-image">
            <div class="card wide-card">
                <img src="{{ asset('images/res.jpg') }}" 
                     alt="PansEat Tagapo Interior">
                <div class="card-overlay">
                    <h3>Dining Experience</h3>  
                    <p>Experience the warm ambiance of our restaurant</p>
                </div>
            </div>
        </div>
        <div class="centered-text">
            <p>
                Whether you're here for a quick meal, a family celebration, or just to satisfy your pancit cravings, 
                we've got something special for you. Our commitment to quality and authenticity is at the core of 
                everything we do. Every dish is crafted with care, ensuring that every customer leaves with a full 
                belly and a happy heart. Come dine with us and experience the legacy of PansEat Tagapo. Whether 
                you're dining in, taking out, or ordering for delivery, we're here to bring you the best of Filipino 
                flavors.
            </p>
        </div>
    </div>
</div>
    <footer>
        <p class="contacts">panseattagapo@gmail.com</p>
        <p class="contacts">09817396111</p>
    </footer>

    
</body>
</html>