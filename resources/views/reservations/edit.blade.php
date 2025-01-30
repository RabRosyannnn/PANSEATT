<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Reservation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/Redit.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/panseat_logo.png') }}">
    <script>
        function validateDeposit(input) {
            const totalPrice = parseFloat(document.getElementById('total_price').value) || 0; // Get the total price
            const depositValue = parseFloat(input.value) || 0; // Get the deposit value

            // Check if the deposit is greater than the total price
            if (depositValue > totalPrice) {
                alert("Deposit cannot be greater than the total price.");
                input.value = ''; // Reset the deposit input
            }
        }

        function updateTotalPrice() {
            let totalPrice = 0; // Initialize total price
            const checkboxes = document.querySelectorAll('input[name="bundles[]"]:checked');

            checkboxes.forEach((checkbox) => {
                const bundlePriceText = checkbox.nextElementSibling.innerText.split('- $')[1];
                const bundlePrice = parseFloat(bundlePriceText);
                const quantityInput = document.getElementById(`quantity_${checkbox.value}`);
                const quantity = parseInt(quantityInput.value) || 1; // Get quantity or default to 1
                totalPrice += bundlePrice * quantity; // Calculate total price
            });

            document.getElementById('total_price').value = totalPrice.toFixed(2); // Update total price display
        }

        function toggleQuantityInput(checkbox) {
            const quantityGroup = document.getElementById(`quantity-group-${checkbox.value}`);
            if (checkbox.checked) {
                quantityGroup.style.display = 'flex'; // Show the quantity input
            } else {
                quantityGroup.style.display = 'none'; // Hide the quantity input
                document.getElementById(`quantity_${checkbox.value}`).value = 1; // Reset quantity to 1
                updateTotalPrice(); // Update total price when unchecked
            }
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Edit Reservation</h2>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('reservations.update', $reservation->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-section">
                        <div class="form-column">
                            <div class="form-group">
                                <label for="customer_name">Customer Name</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ old('customer_name', $reservation->customer_name) }}" required maxlength="25">
                            </div>
                            <div class="form-group">
                                <label for="contact_information">Contact Information</label>
                                <input type="text" class="form-control" id="contact_information" name="contact_information" value="{{ old('contact_information', $reservation->contact_information) }}" required maxlength="11" pattern="^09\d{9}$" title="Contact number must start with '09' and be exactly 11 digits." placeholder="(e.g., 09123456789)">
                            </div>
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $reservation->date) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="number_of_guests">Number of Guests</label>
                                <input type="number" class="form-control" id="number_of_guests" name="number_of_guests" value="{{ old('number_of_guests', $reservation->number_of_guests) }}" required min="1" max="20">
                            </div>
                            <div class="form-group">
                                <label for="start_time">Start Time</label>
                                <input type="time" name="start_time" id="start_time" class="form-control" value="{{ old('start_time', $reservation->start_time) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="end_time">End Time</label>
                                <input type="time" name="end_time" id="end_time" class="form-control" value="{{ old('end_time', $reservation->end_time) }}" required>
                            </div>
                        </div>

                        <div class="form-column">
                            <div class="form-group">
                                <label for="booking_confirmation">Booking Status</label>
                                <select name="booking_confirmation" id="booking_confirmation" class="form-control">
                                    <option value="processing" {{ old('booking_confirmation', $reservation->booking_confirmation) == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="confirmed" {{ old('booking_confirmation', $reservation->booking_confirmation) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="cancelled" {{ old('booking_confirmation', $reservation->booking_confirmation) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="complete" {{ old('booking_confirmation', $reservation->booking_confirmation) == 'complete' ? 'selected' : '' }}>Complete</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="deposit">Deposit</label>
                                <input type="number" class="form-control" id="deposit" name="deposit" step="0.01" min="0" value="{{ old('deposit', $reservation->deposit) }}" oninput="validateDeposit(this)">
                            </div>
                            <div class="form-group">
                                <label for="occasion">Occasion</label>
                                <input type="text" class="form-control" id="occasion" name="occasion" value="{{ old('occasion', $reservation->occasion) }}" maxlength="25">
                            </div>
                            <div class="form-group">
                                <label for="bundles">Select Bundles</label>
                                <div>
                                    @foreach($activeBundles as $bundle)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="bundle_{{ $bundle->id }}" name="bundles[]" value="{{ $bundle->id }}" {{ $reservation->bundles->contains($bundle->id) ? 'checked' : '' }} onchange="toggleQuantityInput(this); updateTotalPrice()">
                                            <label class="form-check-label" for="bundle_{{ $bundle->id }}">
                                                {{ $bundle->name }} - ${{ number_format($bundle->price, 2) }}
                                            </label>
                                            <div class="input-group quantity-control" id="quantity-group-{{ $bundle->id }}" style="display: {{ $reservation->bundles->contains($bundle->id) ? 'flex' : 'none' }}; margin-top: 5px;">
                                                <input type="number" class="form-control text-center" id="quantity_{{ $bundle->id }}" name="quantities[{{ $bundle->id }}]" value="{{ old('quantities.'.$bundle->id, 1) }}" min="1" max="10" onchange="updateTotalPrice()">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="note">Note</label>
                                <textarea class="form-control" id="note" name="note" rows="3" placeholder="Enter additional notes here...">{{ old('note', $reservation->note) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-secondary">Close</a>    
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>