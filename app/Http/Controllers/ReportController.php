<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function generate(Request $request)
    {
        // Fetch data
        $completedReservations = 123; // Example data

        // Retrieve the Base64 chart image
        $chartImage = $request->input('chartImage');

        // Pass data and the chart image to the view
        $data = [
            'completedReservations' => $completedReservations,
            'chartImage' => $chartImage,
        ];

        // Generate the PDF
        $pdf = Pdf::loadView('reports.completed-reservations', $data);

        return $pdf->download('completed_reservations_report.pdf');
    }
}
