<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Reservation Details</h2>
        <div class="card">
        <div class="card-body">
    <!-- Tracking ID -->
    <p class="card-text text-muted"><strong>Tracking ID:</strong> {{ $reservation->tracking_id ?? 'N/A' }}</p>
    
    <!-- Customer Name -->
    <h5 class="card-title">Customer Name: {{ $reservation->customer_name }}</h5>
    
    <!-- Other Reservation Details -->
    <p class="card-text"><strong>Contact Information:</strong> {{ $reservation->contact_information }}</p>
    <p class="card-text"><strong>Date:</strong> {{ \Carbon\Carbon::parse($reservation->date)->format('F j, Y') }}</p>
    <p class="card-text"><strong>Start Time:</strong> {{ \Carbon\Carbon::parse($reservation->start_time)->format('g:i A') }}</p>
    <p class="card-text"><strong>End Time:</strong> {{ \Carbon\Carbon::parse($reservation->end_time)->format('g:i A') }}</p>
    <p class="card-text"><strong>Number of Guests:</strong> {{ $reservation->number_of_guests }}</p>
    <p class="card-text"><strong>Booking Confirmation:</strong> {{ $reservation->booking_confirmation ? 'Yes' : 'No' }}</p>
    <p class="card-text"><strong>Deposit:</strong> {{ $reservation->deposit ? '$' . number_format($reservation->deposit, 2) : 'N/A' }}</p>
    <p class="card-text"><strong>Occasion:</strong> {{ $reservation->occasion ?? 'N/A' }}</p>
    
    <!-- Bundles -->
    <p class="card-text"><strong>Bundles:</strong></p>
    <ul>
        @forelse($reservation->bundles as $bundle)
            <li>{{ $bundle->name }} - ${{ number_format($bundle->price, 2) }}</li>
        @empty
            <li>No bundles selected for this reservation.</li>
        @endforelse
    </ul>
    
    <!-- Note -->
    <p class="card-text"><strong>Note:</strong> {{ $reservation->note ?? 'No additional notes provided.' }}</p>
</div>

        </div>
        <div class="mt-4">
            <a href="{{ route('reservations.edit', $reservation->id) }}" class="btn btn-primary">Edit Reservation</a>
            <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this reservation?');">Delete Reservation</button>
            </form>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
