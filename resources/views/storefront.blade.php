<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PansEat Tagapo</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
        .map-container {
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            margin: 20px 0; /* Add some margin for spacing */
            height: 450px; /* Set a fixed height for the container */
        }

        .map-container iframe {
            width: 100%; /* Make the iframe responsive */
            height: 100%; /* Fill the height of the container */
            border: 0; /* Remove border */
        }
    </style>
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
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3865.889848272707!2d121.10210607577037!3d14.317830483935841!2m3!1f0! 2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d9b8db80dd7d%3A0x463744aee1730445!2slmc%20original%20pancit%20tagapo!5e0!3m2!1sen!2sph!4v1736986204983!5m2!1sen!2sph" width="600" height="450" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>        
        

        <div class="calendar-section">
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#bundlesModal">View Bundles</button>
            <div id="reservationCalendar"></div>
        </div>
        <!-- Modal for displaying bundles -->
        <div class="modal fade" id="bundlesModal" tabindex="-1" role="dialog" aria-labelledby="bundlesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg custom-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bundlesModalLabel">Bundles</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            @foreach($activeBundles as $bundle)
                                <li class="list-group-item d-flex align-items-center">
                                    @if($bundle->image)
                                        <img src="{{ asset('storage/' . $bundle->image) }}" alt="{{ $bundle->name }}" class="bundle-image img-thumbnail" style="width: 150px; height: auto; margin-right: 10px;">
                                    @endif
                                    <div>
                                        <strong>{{ $bundle->name }}</strong>
                                        <p>{{ $bundle->description }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p class="contacts">panseattagapo@gmail.com</p>
        <p class="contacts">09817396111</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('reservationCalendar');

            if (!calendarEl) {
                console.error('Element with id "reservationCalendar" not found.');
                return;
            }

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: '{{ route('events.get') }}',
                eventClick: function (info) {
                    info.jsEvent.preventDefault();
                }
            });
            calendar.render();
        });
    </script>
</body>
</html>