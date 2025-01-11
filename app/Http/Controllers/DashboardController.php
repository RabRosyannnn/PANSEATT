<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bundle;
use App\Models\Reservation; // Import the Reservation model
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $activeStaff = User::where('is_archived', false)->get();
        $archivedStaff = User::where('is_archived', true)->get();
        $activeBundles = Bundle::where('is_archived', false)->get();
        $archivedBundles = Bundle::where('is_archived', true)->get();
        

        return view('dashboard', compact('activeBundles', 'archivedBundles', 'activeStaff', 'archivedStaff', ));
    }

    public function getEvents()
    {
        // Fetch reservations from the database
        $reservations = Reservation::all();

        // Transform the data into the format FullCalendar expects
        $events = $reservations->map(function ($reservation) {
            return [
                'title' => $reservation->customer_name . ' (' . $reservation->number_of_guests . ' guests)',
                'start' => $reservation->date . 'T' . $reservation->time, // Combine date and time
                'allDay' => false,
            ];
        });

        return response()->json($events);
    }
}