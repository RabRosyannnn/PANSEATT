<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bundle;
use App\Models\Reservation; // Import the Reservation model
use Illuminate\Http\Request;
use App\Models\StaffLog;
use Illuminate\Support\Facades\Auth;
use App\Models\ReservationRequest;
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
    $completedReservations = Reservation::where('booking_confirmation', 'complete')->count();
    $modelRequests = ReservationRequest::all();
    
    // Using MONTHNAME instead of MONTH
    $monthlyData = Reservation::selectRaw('MONTHNAME(created_at) as month, COUNT(*) as count')
        ->where('booking_confirmation', 'complete')
        ->groupBy('month')
        ->orderByRaw('MONTH(created_at)')
        ->pluck('count', 'month')
        ->toArray();

    // Initialize array with month names
    $months = ['January', 'February', 'March', 'April', 'May', 'June', 
               'July', 'August', 'September', 'October', 'November', 'December'];
    $monthlyCounts = array_fill_keys($months, 0);
    
    // Fill in actual counts
    foreach ($monthlyData as $month => $count) {
        $monthlyCounts[$month] = $count;
    }

    return view('dashboard', compact('activeBundles', 'archivedBundles', 'activeStaff', 
                'archivedStaff', 'staffLogs', 'reservations', 'modelRequests',
                'completedReservations', 'monthlyCounts'));
}

public function getEvents()
{
    // Fetch reservations that are not canceled
    $reservations = Reservation::whereIn('booking_confirmation', ['processing', 'confirmed'])
        ->get();
    
    $events = $reservations->map(function ($reservation) {
        return [
            'title' => $reservation->tracking_id . ' (' . $reservation->number_of_guests . ' guests)',
            'start' => $reservation->date . 'T' . $reservation->start_time,
            'end' => $reservation->date . 'T' . $reservation->end_time,
            'allDay' => false,
        ];
    });

    return response()->json($events);
}
}