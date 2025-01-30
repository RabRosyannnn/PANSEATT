<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Details</title>
    <link rel="stylesheet" href="{{ asset('css/reservation-details.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/panseat_logo.png') }}">
</head>
<body>
<h1 class="title">TRACK</h1>
<a href="{{route('home')}}" class="back">Back</a>
<div class="container">
    <div class="cards">
        <div class="cardheader">
            <h1>Details</h1>
        </div>
        @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
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
                        <p><strong>Deposit:</strong>  {{ $reservation->deposit }}</p>
                    @endif
                    <p><strong>Occasion:</strong> {{ $reservation->occasion }}</p>
                    @if($reservation->note)
                        <p><strong>Notes:</strong> {{ $reservation->note }}</p>
                    @endif
                    <p><strong>Price:</strong> {{ $reservation->price }} php</p>
                </div>
                 
                <div class="details-card confirmation-status">
                    <h3>Booking Confirmation Status</h3>
                    <div class="status-display {{ strtolower($reservation->booking_confirmation) }}">
                        {{ strtoupper($reservation->booking_confirmation) }}
                    </div>
                </div>
                <!-- New Section for Bundles and Quantities -->
                <div class="details-card bundles-info">
                    <h3>Selected Bundles</h3>
                    <ul>
                        @foreach($reservation->bundles as $bundle)
                            <li>
                                {{ $bundle->name }} - Quantity: {{ $bundle->pivot->quantity }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="mt-4 button-container">
            <!-- Request Change Button -->
            <button class="btn btn-warning" data-toggle="modal" data-target="#changeRequestModal">Request Change</button>

            <!-- Request Cancellation Button -->
            <button class="btn btn-danger" data-toggle="modal" data-target="#cancellationRequestModal">Request Cancellation</button>
        </div>
    </div>
</div>

<!-- Change Request Modal -->
<div class="modal fade" id="changeRequestModal" tabindex="-1" role="dialog" aria-labelledby="changeRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeRequestModalLabel">Request Change</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('requests.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="tracking_id" value="{{ $reservation->tracking_id }}">
                    <div class="form-group">
                        <label for="change_message">Change Request Message</label>
                        <textarea class="form-control" id="change_message" name="message" rows="3" required></textarea>
                    </div>
                    <input type="hidden" name="action" value="change">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit " class="btn btn-warning">Submit Change Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancellation Request Modal -->
<div class="modal fade" id="cancellationRequestModal" tabindex="-1" role="dialog" aria-labelledby="cancellationRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancellationRequestModalLabel">Request Cancellation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('requests.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="tracking_id" value="{{ $reservation->tracking_id }}">
                    <div class="form-group">
                        <label for="cancellation_reason">Reason for Cancellation</label>
                        <textarea class="form-control" id="cancellation_reason" name="message" rows="3" required></textarea>
                    </div>
                    <input type="hidden" name="action" value="cancel">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Submit Cancellation Request</button>
                </div>
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