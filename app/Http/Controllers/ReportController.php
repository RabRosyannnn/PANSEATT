<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReportMail;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function generate(Request $request)
{
    // Fetch reservations based on filters (month/year)
    $month = $request->input('month');
    $year = $request->input('year');

    $query = Reservation::where('booking_confirmation', 'complete');

    if ($month) {
        $query->whereMonth('created_at', $month);
    }

    if ($year) {
        $query->whereYear('created_at', $year);
    }

    $completedReservations = $query->get();

    // Retrieve the Base64 chart image
    $chartImage = $request->input('chartImage');

    // Prepare data for the PDF
    $data = [
        'completedReservations' => $completedReservations,
        'chartImage' => $chartImage,
    ];

    // Generate the PDF
    $pdf = Pdf::loadView('reports.completed-reservations', $data)->output();

    // Get the authenticated user's email
    $userEmail = Auth::user()->email;

    // Send the email with the generated PDF attached
    Mail::to($userEmail)->send(new ReportMail($data, $pdf));

    // Return success message with the user's email
    return back()->with('success', 'Report sent successfully to ' . $userEmail);
}

}