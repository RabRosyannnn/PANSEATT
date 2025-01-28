<?php

namespace App\Http\Controllers;
use App\Models\StaffLog;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bundle;

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
            'start' => $reservation->date . 'T' . $reservation->time,
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
    // Store a newly created reservation in storage
    public function store(Request $request)
{
    try {
        // Validate the incoming request data
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
        ]);

        // Check if there is an existing reservation at the same time
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

        // Generate tracking ID
        $trackingId = random_int(10000, 99999);

        // Create the reservation with the tracking ID
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
            'note' => $validatedData['note'] ?? null,  // Handle optional field 'note'
            'tracking_id' => $trackingId,  // Add the generated tracking ID
        ]);

        // Attach selected bundles
        $reservation->bundles()->attach($validatedData['bundles']);

        // Log action
        $this->logAction('create', "Created a reservation with ID: {$trackingId}");

        return response()->json([
            'success' => true,
            'message' => 'Reservation created successfully.',
            'reservation' => $reservation,
        ]);
    } catch (\Exception $e) {
        \Log::error('Error creating reservation: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'There was an error while creating the reservation. Please try again later.',
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

        return view('reservations.edit', compact('reservation','activeBundles'));
    }

    // Update the specified reservation in storage
    public function update(Request $request, $id)
{
    // Validate the request
    $request->validate([
        'customer_name' => 'required|string|max:255',
        'contact_information' => 'required|string|max:255',
        'date' => 'required|date',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time', // Ensure end time is after start time
        'number_of_guests' => 'required|integer|min:1',
        'booking_confirmation' => 'required|string|in:processing,confirmed,canceled,complete', // Ensure the value is one of the specified options
        'deposit' => 'required|numeric',
        'occasion' => 'required|string|max:255',
        'bundles' => 'required|array', // Change 'bundle' to 'bundles'
        'bundles.*' => 'required|integer|exists:bundles,id', // Ensure each selected bundle ID exists in the bundles table
        'note' => 'nullable|string|max:255',
    ]);

    // Find the reservation by ID
    $reservation = Reservation::findOrFail($id);

    // Update the reservation with the validated data
    $reservation->update($request->all());

    // Log the action
    $this->logAction('update', "Updated the reservation for {$reservation->customer_name}");

    // Redirect with success message
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
                'start' => $reservation->date . 'T' . $reservation->time,
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

}