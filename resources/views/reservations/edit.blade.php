<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Reservation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Reservation</h2>
        <form action="{{ route('reservations.update', $reservation->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="customer_name">Customer Name</label>
                <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ $reservation->customer_name }}" required>
            </div>
            <div class="form-group">
                <label for="contact_information">Contact Information</label>
                <input type="text" class="form-control" id="contact_information" name="contact_information" value="{{ $reservation->contact_information }}" required>
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ $reservation->date }}" required>
            </div>
            <div class="form-group">
                <label for="time">Time</label>
                <input type="time" class="form-control" id="time" name="time" value="{{ $reservation->time }}" required>
            </div>
            <div class="form-group">
                <label for="number_of_guests">Number of Guests</label>
                <input type="number" class="form-control" id="number_of_guests" name="number_of_guests" value="{{ $reservation->number_of_guests }}" required>
            </div>
            <div class="form-group">
                <label for="booking_confirmation">Booking Confirmation</label>
                <select class="form-control" id="booking_confirmation" name="booking_confirmation" required>
                    <option value="1" {{ $reservation->booking_confirmation ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !$reservation->booking_confirmation ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="deposit">Deposit (optional)</label>
                <input type="number" class="form-control" id="deposit" name="deposit" step="0.01" value="{{ $reservation->deposit }}">
            </div>
            <div class="form-group">
                <label for="occasion">Occasion</label>
                <input type="text" class="form-control" id="occasion" name="occasion" value="{{ $reservation->occasion }}">
            </div>
            <div class="form-group">
                <label for="bundle">Bundle</label>
                <input type="text" class="form-control" id="bundle" name="bundle" value="{{ $reservation->bundle }}">
            </div>
            <button type="submit" class="btn btn-success">Save Changes</button>
            <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
