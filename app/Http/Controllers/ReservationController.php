<?php

namespace App\Http\Controllers;

use App\Models\StaffLog;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bundle;
use Illuminate\Support\Facades\Http; // Make sure to import Http

class ReservationController extends Controller
{
    // Display a listing of the reservations
    public function index()
    {
        $reservations = Reservation::all();

        $events = $reservations->map(function ($reservation) {
            return [
                'id' => $reservation->id,
                'title' => $reservation->customer_name,
                'start' => $reservation->date . 'T' . $reservation->start_time,
            ];
        });

        return response()->json($events);
    }

    // Show the form for creating a new reservation
    public function create()
    {
        return view('reservations.create');
    }

    // Store a newly created reservation in storage
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'contact_information' => 'required|string|size:11|regex:/^09\d{9}$/',
                'customer_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'date' => 'required|date',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'number_of_guests' => 'required|integer|min:1',
                'booking_confirmation' => 'required|string|in:processing,confirmed,cancelled',
                'deposit' => 'required|numeric|min:0',
                'occasion' => 'required|string|max:255',
                'bundles' => 'required|array|min:1',
                'bundles.*' => 'integer|exists:bundles,id',
                'note' => 'nullable|string|max:255',
                'total_price' => 'required|numeric|min:0', // Add validation for total_price
            ]);

            // Prevent duplicate reservations
            $existingReservation = Reservation::where('date', $validatedData['date'])
                ->where(function ($query) use ($validatedData) {
                    $query->whereBetween('start_time', [$validatedData['start_time'], $validatedData['end_time']])
                          ->orWhereBetween('end_time', [$validatedData['start_time'], $validatedData['end_time']])
                          ->orWhere(function ($query) use ($validatedData) {
                              $query->where('start_time', '<=', $validatedData['start_time'])
                                    ->where('end_time', '>=', $validatedData['end_time']);
                          });
                })
                ->exists();

            if ($existingReservation) {
                return response()->json([
                    'success' => false,
                    'message' => 'A reservation already exists with the same date and overlapping time range.',
                ], 400);
            }

            // Validate that the deposit does not exceed the total price
            if ($validatedData['deposit'] > $validatedData['total_price']) {
                return response()->json([
                    'success' => false,
                    'message' => 'The deposit cannot be greater than the total price of the reservation.',
                ], 400);
            }

            // Generate tracking ID
            $trackingId = random_int(10000, 99999);

            // Create reservation with total price from request
            $reservation = Reservation::create([
                'contact_information' => $validatedData['contact_information'],
                'customer_name' => $validatedData['customer_name'],
                'date' => $validatedData['date'],
                'start_time' => $validatedData['start_time'],
                'end_time' => $validatedData['end_time'],
                'number_of_guests' => $validatedData['number_of_guests'],
                'booking_confirmation' => $validatedData['booking_confirmation'],
                'deposit' => $validatedData['deposit'],
                'occasion' => $validatedData['occasion'],
                'note' => $validatedData['note'] ?? null,
                'tracking_id' => $trackingId,
                'price' => $validatedData['total_price'], // Use the total price from the request
            ]);

            // Attach selected bundles
            $reservation->bundles()->attach($validatedData['bundles']);

            // Log action
            $this->logAction('create', "Created a reservation with ID: {$trackingId}");

            // Step 4: Get the contact information
            $contactInformation = $reservation->contact_information;

            // Step 5: Transform the contact number (only replacing the leading '0' with '+63')
            $contactInformation = $this->transformContactNumber($contactInformation);

            // Prepare the message for SMS
            $defaultMessage = "Thank you for your reservation! Your tracking ID is {$trackingId}. Visit our website for details of your reservation.";
            $message = $defaultMessage;

            // Send SMS
            $this->sendSms($contactInformation, $message);

            return response()->json([
                'success' => true,
                'message' => 'Reservation created successfully.',
                'reservation' => $reservation,
            ]);
        } catch (\Exception $e) {
            // Log the error message and stack trace
            \Log::error('Error creating reservation: ' . $e->getMessage());
            \Log::error($e->getTraceAsString()); // Log the stack trace for more context

            return response()->json([
                'success' => false,
                'message' => 'There was an error while creating the reservation. Please try again later.',
            ], 500);
        }
    }

    // Method to send SMS
    private function sendSms($contactInformation, $message)
    {
        $response = Http::withHeaders([
            'Authorization' => 'd1f02324-c1fa-4490-9059-c2860ac5df6d'
        ])->post('http://192.168.1.17:8082/sms-endpoint', [
            'to' => $contactInformation,
            'message' => $message
        ]);

        // Check if the SMS was sent successfully
        if ($response->failed()) {
            \Log::error('SMS sending failed: ' . $response->body());
            return response()->json([
                'success' => false,
                'message' => 'Reservation created, but failed to send SMS notification.',
            ], 500);
        }
    }

    // Display the specified reservation
    public function show($id)
    {
        // Load the reservation along with its bundles
        $reservation = Reservation::with('bundles')->findOrFail($id);
        
        return view('reservations.show', compact('reservation'));
    }

    // Show the form for editing the specified reservation
    public function edit($id)
    {
        $activeBundles = Bundle::where('is_archived', false)->get();
        $reservation = Reservation::findOrFail($id); // Retrieve the reservation by ID

        // Format the time fields to H:i format
        $reservation->start_time = \Carbon\Carbon::parse($reservation->start_time)->format('H:i');
        $reservation->end_time = \Carbon\Carbon::parse($reservation->end_time)->format('H:i');

        return view('reservations.edit', compact('reservation', 'activeBundles'));
    }

    // Update the specified reservation in storage
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'contact_information' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'number_of_guests' => 'required|integer|min:1',
            'booking_confirmation' => 'required|string|in:processing,confirmed,cancelled,complete',
            'deposit' => 'required|numeric',
            'occasion' => 'required|string|max:255',
            'bundles' => 'required|array',
            'bundles.*' => 'required|integer|exists:bundles,id',
            'note' => 'nullable|string|max:255',
        ]);

        // Find the reservation by ID
        $reservation = Reservation::findOrFail($id);

        // Recalculate the price
        $basePrice = 500;
        $bundleTotal = Bundle::whereIn('id', $validatedData['bundles'])->sum('price');
        $totalPrice = $basePrice + $bundleTotal;

        // Update the reservation
        $reservation->update(array_merge($validatedData, ['price' => $totalPrice]));

        // Update bundle associations
        $reservation->bundles()->sync($validatedData['bundles']);

        // Log the action
        $this->logAction('update', "Updated the reservation for {$reservation->customer_name}");

        return redirect()->route('reservations.show', $id)
                         ->with('success', 'Reservation updated successfully.');
    }

    // Remove the specified reservation from storage
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id); // Retrieve the reservation by ID
        $reservation->delete();
        $this->logAction('deleted', "Deleted the reservation for {$reservation->customer_name}");
        return redirect()->route('dashboard')->with('success', 'Reservation deleted successfully.');
    }

    public function getReservations()
    {
        $reservations = Reservation::all()->map(function ($reservation) {
            return [
                'title' => $reservation->customer_name,
                'start' => $reservation->date . 'T' . $reservation->start_time,
                'description' => $reservation->occasion,
            ];
        });

        return response()->json($reservations);
    }

    public function getEvents()
    {
        $reservations = Reservation::where('booking_confirmation', '!=', 'complete')->get();

        $events = $reservations->map(function ($reservation) {
            return [
                'id' => $reservation->id,
                'title' => $reservation->tracking_id ,
                'start' => $reservation->date . 'T' . $reservation->start_time, // Format: YYYY-MM-DDTHH:MM
                'end' => $reservation->date . 'T' . $reservation->end_time, // Format: YYYY-MM-DDTHH:MM
                'allDay' => false, // Set to false for time-based events
            ];
        });

        return response()->json($events);
    }

    private function logAction($action, $description)
    {
        StaffLog::create([
            'user_id' => Auth::id(), // Assuming you are using Laravel's Auth system
            'action' => $action,
            'description' => $description,
        ]);
    }

    public function customerShow($id)
    {
        $reservation = Reservation::with('bundles')->findOrFail($id);
        return view('reservations.customer-show', compact('reservation'));
    }

    public function trackReservation(Request $request)
    {
        $trackingId = $request->input('trackingId');
        
        // Find the reservation by the tracking ID
        $reservation = Reservation::where('tracking_id', $trackingId)->first();
        
        if (!$reservation) {
            return redirect()->back()->with('error', 'Reservation not found with the provided tracking ID.');
        }
        
        // Redirect to the customer view instead of the admin view
        return redirect()->route('reservations.customerShow', $reservation->id);
    }

    private function transformContactNumber($contactNumber)
    {
        // Check if the number starts with '0' and replace it with '+63'
        if (substr($contactNumber, 0, 1) === '0') {
            $contactNumber = '+63' . substr($contactNumber, 1);
        }

        return $contactNumber;
    }
}