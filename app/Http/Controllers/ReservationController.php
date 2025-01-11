<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    // Display a listing of the reservations
    public function index()
    {
        $reservations = Reservation::all();
        return view('dashboard', compact('reservations'));
    }

    // Show the form for creating a new reservation
    public function create()
    {
        return view('reservations.create');
    }

    // Store a newly created reservation in storage
    public function store(Request $request)
    {
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

        Reservation::create($request->all());

        return redirect()->route('dashboard')->with('success', 'Reservation created successfully.');
    }

    // Display the specified reservation
    public function show(Reservation $reservation)
    {
        return view('reservations.show', compact('reservation'));
    }

    // Show the form for editing the specified reservation
    public function edit(Reservation $reservation)
    {
        return view('reservations.edit', compact('reservation'));
    }

    // Update the specified reservation in storage
    public function update(Request $request, Reservation $reservation)
    {
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

        $reservation->update($request->all());

        return redirect()->route('dashboard')->with('success', 'Reservation updated successfully.');
    }

    // Remove the specified reservation from storage
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

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
            'title' => $reservation->customer_name . ' (' . $reservation->number_of_guests . ' guests)',
            'start' => $reservation->date . 'T' . $reservation->time,
            'allDay' => false,
        ];
    });

    return response()->json($events);
}

}