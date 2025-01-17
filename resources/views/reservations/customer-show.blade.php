<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Details</title>
    <link rel="stylesheet" href="{{ asset('css/reservation-details.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
<h1 class="title">TRACK</h1>
         <!-- Back to Search -->
         <a href="{{route('home')}}" class="back">Back</a>
    <div class="container">
        
        <div class="cards">
            <div class="cardheader">
                <h1>Details</h1>
            </div>
            <div class="card-body">
                <div class="content-wrapper">
                    <div class="details-card customer-info">
                        <h3>{{ $reservation->customer_name }}</h3>
                        <p><strong>Tracking ID:</strong> {{ $reservation->tracking_id }}</p>
                        <p><strong>Contact Information:</strong> {{ $reservation->contact_information }}</p>
                        <p><strong>Date & Time:</strong> 
                            {{ \Carbon\Carbon::parse($reservation->date)->format('m/d/Y') }} - 
                            {{ \Carbon\Carbon::parse($reservation->start_time)->format('g:i A') }} to 
                            {{ \Carbon\Carbon::parse($reservation->end_time)->format('g:i A') }}</p>
                        <p><strong>Number of Guests:</strong> {{ $reservation->number_of_guests }}</p>
                        @if($reservation->deposit)
                            <p><strong>Deposit:</strong> Paid</p>
                        @endif
                        <p><strong>Occasion:</strong> {{ $reservation->occasion }}</p>
                        @if($reservation->note)
                            <p><strong>Notes:</strong> {{ $reservation->note }}</p>
                        @endif
                    </div>

                    <div class="details-card confirmation-status">
                        <h3>Booking Confirmation Status</h3>
                        <div class="status-display {{ strtolower($reservation->booking_confirmation) }}">
                            {{ strtoupper($reservation->booking_confirmation) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 button-container">
    <!-- Request Change Button -->
    <form action="" method="GET" style="display:inline;">
        @csrf
        <button type="submit" class="btn btn-warning">Request Change</button>
    </form>

    <!-- Request Cancellation Button -->
    <form action="" method="POST" style="display:inline;">
        @csrf
        @method('POST')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to request cancellation of this reservation?');">Request Cancellation</button>
    </form>
</div>
        </div>
    </div>

    <!-- Font Awesome for search icon -->
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<footer>
        <p class="contacts">panseattagapo@gmail.com</p>
        <p class="contacts">09817396111</p>
    </footer>
</html>
