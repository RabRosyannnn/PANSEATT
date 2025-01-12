<?php

namespace App\Http\Controllers;
use App\Models\StaffLog;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function store(Request $request)
{
    try {
        // Validate the request
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'contact_information' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'number_of_guests' => 'required|integer|min:1',
            'booking_confirmation' => 'boolean',
            'deposit' => 'nullable|numeric',
            'occasion' => 'nullable|string|max:255',
            'bundle' => 'nullable|string|max:255',
        ]);

        // Create a new reservation
        $reservation = Reservation::create($request->all());

        // Log the action
        $this->logAction('create', "Created a new reservation for {$reservation->customer_name}");

        return redirect()->route('dashboard')->with('success', 'Reservation created successfully.');
    } catch (\Exception $e) {
        // Log the error message
        \Log::error('Error creating reservation: ' . $e->getMessage());

        // Redirect back with an error message
        return redirect()->back()->withErrors(['error' => 'An error occurred while creating the reservation. Please try again.']);
    }
}

    // Display the specified reservation
    public function show($id)
    {
        $reservation = Reservation::findOrFail($id);
        return view('reservations.show', compact('reservation'));
    }

    // Show the form for editing the specified reservation
    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id); // Retrieve the reservation by ID
        return view('reservations.edit', compact('reservation'));
    }

    // Update the specified reservation in storage
    public function update(Request $request, $id)
{
    $request->validate([
        'customer_name' => 'required|string|max:255',
        'contact_information' => 'required|string|max:255',
        'date' => 'required|date',
        'time' => 'required',
        'number_of_guests' => 'required|integer|min:1',
        'booking_confirmation' => 'required|boolean',
        'deposit' => 'nullable|numeric|min:0',
        'occasion' => 'nullable|string|max:255',
        'bundle' => 'nullable|string|max:255',
    ]);

    $reservation = Reservation::findOrFail($id);
    $reservation->update($request->all());
    $this->logAction('update', "updated the reservation for {$reservation->customer_name}");
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
        $events = Reservation::all()->map(function ($reservation) {
            return [
                'title' => $reservation->occasion . ' (' . $reservation->number_of_guests . ' guests)',
                'start' => $reservation->date . 'T' . $reservation->time,
                'allDay' => false,
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
}