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
            const totalPrice = parseFloat(document.getElementById('total_price').value) || 0;
            const depositValue = parseFloat(input.value) || 0;
            if (depositValue > totalPrice) {
                alert("Deposit cannot be greater than the total price.");
                input.value = ''; 
            }
        }

        function updateTotalPrice() {
            let totalPrice = 0;
            const checkboxes = document.querySelectorAll('input[name="bundles[]"]:checked');

            checkboxes.forEach((checkbox) => {
                const bundlePriceText = checkbox.nextElementSibling.innerText.split('- $')[1];
                const bundlePrice = parseFloat(bundlePriceText);
                const quantityInput = document.getElementById(`quantity_${checkbox.value}`);
                const quantity = parseInt(quantityInput.value) || 1;
                totalPrice += bundlePrice * quantity;
            });

            document.getElementById('total_price').value = totalPrice.toFixed(2);
        }

        function toggleQuantityInput(checkbox) {
            const quantityGroup = document.getElementById(`quantity-group-${checkbox.value}`);
            if (checkbox.checked) {
                quantityGroup.style.display = 'flex';
            } else {
                quantityGroup.style.display = 'none';
                document.getElementById(`quantity_${checkbox.value}`).value = 1;
                updateTotalPrice();
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

                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_name">Customer Name</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" required maxlength="25" value="{{ old('customer_name', $reservation->customer_name) }}">
                            </div>
                            <div class="form-group">
                                <label for="contact_number">Contact Number</label>
                                <input type="text" class="form-control" id="contact_information" name="contact_information" required maxlength="11" pattern="^09\d{9}$" title="Must start with '09' and be exactly 11 digits." placeholder="(e.g., 09123456789)"value="{{ old('contact_information', $reservation->contact_information) }}">
                            </div>
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" class="form-control" id="date" name="date" required value="{{ old('date', $reservation->date) }}">
                            </div>
                            <div class="form-group">
                                <label for="number_of_guests">Number of Guests</label>
                                <input type="number" class="form-control" id="number_of_guests" name="number_of_guests" required min="1" max="20" value="{{ old('number_of_guests', $reservation->number_of_guests) }}">
                            </div>
                            <div class="form-group">
                                <label for="start_time">Start Time</label>
                                <input type="time" name="start_time" id="start_time" class="form-control" required value="{{ old('start_time', $reservation->start_time) }}">
                            </div>
                            <div class="form-group">
                                <label for="end_time">End Time</label>
                                <input type="time" name="end_time" id="end_time" class="form-control" required value="{{ old('end_time', $reservation->end_time) }}">
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="booking_confirmation">Booking Status</label>
                                <select name="booking_confirmation" id="booking_confirmation" class="form-control">
                                    <option value="processing" {{ old('booking_confirmation', 'processing') == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="confirmed" {{ old('booking_confirmation') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="complete" {{ old('booking_confirmation') == 'complete' ? 'selected' : '' }}>Complete</option>
                                    <option value="cancelled" {{ old('booking_confirmation') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="bundles">Select Bundles</label>
                                <div style="max-height: 150px; overflow-y: auto; border: 1px solid #ccc; padding: 10px;">
                                    @foreach($activeBundles as $bundle)
                                        <div class="form-check d-flex align-items-center mb-2">
                                            <input class="form-check-input" type="checkbox" id="bundle_{{ $bundle->id }}" name="bundles[]" value="{{ $bundle->id }}"{{ in_array($bundle->id, old('bundles', $reservation->bundles->pluck('id')->toArray())) ? 'checked' : '' }} onchange="toggleQuantityInput(this)">
                                            <label class="form-check-label me-2" for="bundle_{{ $bundle->id }}">
                                                {{ $bundle->name }} - ${{ number_format($bundle->price, 2) }}
                                            </label>
                                            <div class="input-group quantity-control" id="quantity-group-{{ $bundle->id }}" style="display: none; width: 60px; margin-left:10px">
                                                <input type="number" class="form-control text-center" id="quantity_{{ $bundle->id }}" name="quantities[{{ $bundle->id }}]" value="{{ old('quantities.' . $bundle->id, $reservation->quantities[$bundle->id] ?? 1) }}" min="1" max="10" style="width: 50px;" onchange="updateTotalPrice();">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="total_price">Total Price</label>
                                <input type="text" class="form-control" id="total_price" name="total_price" readonly value="{{ old('price', $reservation->price) }}">
                            </div>
                            <div class="form-group">
                                <label for="deposit">Deposit</label>
                                <input type="number" class="form-control" id="deposit" name="deposit" step="0.01" min="0" required max="99999.99" oninput="validateDeposit(this)" value="{{ old('deposit', $reservation->deposit) }}">
                            </div>
                            <div class="form-group">
                                <label for="occasion">Occasion</label>
                                <input type="text" class="form-control" id="occasion" name="occasion" required maxlength="25" value="{{ old('occasion', $reservation->occasion) }}">
                            </div>
                            <div class="form-group">
                                <label for="note">Note</label>
                                <textarea class="form-control" id="note" name="note" rows="3" placeholder="Enter additional notes here...">{{ old('note', $reservation->note) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-secondary">Cancel</a>    
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
