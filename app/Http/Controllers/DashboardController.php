<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bundle;
use App\Models\Reservation; // Import the Reservation model
use Illuminate\Http\Request;
use App\Models\StaffLog;
use Illuminate\Support\Facades\Auth;
use App\Models\Request as ModelsRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $activeStaff = User::where('is_archived', false)->get();
        $archivedStaff = User::where('is_archived', true)->get();
        $activeBundles = Bundle::where('is_archived', false)->get();
        $archivedBundles = Bundle::where('is_archived', true)->get();
        $staffLogs = StaffLog::latest()->paginate(10);
        $reservations = Reservation::all();
        $modelRequests = ModelsRequest::all();
        return view('dashboard', compact('activeBundles', 'archivedBundles', 'activeStaff', 'archivedStaff', 'staffLogs', 'reservations', 'modelRequests'));
    }

    public function getEvents()
{
    $reservations = Reservation::all();
    
    $events = $reservations->map(function ($reservation) {
        return [
            'title' => $reservation->customer_name . ' (' . $reservation->number_of_guests . ' guests)',
            'start' => $reservation->date . 'T' . $reservation->start_time,
            'end' => $reservation->date . 'T' . $reservation->end_time,
            'allDay' => false,
        ];
    });

    return response()->json($events);
}
}