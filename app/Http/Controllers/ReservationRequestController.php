<?php

namespace App\Http\Controllers;

use App\Models\ReservationRequest;
use Illuminate\Http\Request;

class ReservationRequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tracking_id' => 'required|string',
            'message' => 'required|string',
            'action' => 'required|in:change,cancel'
        ]);

        ReservationRequest::create($validated);

        return redirect()->back()->with('success', 'Your request has been submitted successfully.');
    }

    public function index()
    {
        $requests = ReservationRequest::with('reservation')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.requests.index', compact('requests'));
    }

    public function update(Request $request, ReservationRequest $reservationRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_response' => 'required|string'
        ]);

        $reservationRequest->update($validated);

        return redirect()->back()->with('success', 'Request status updated successfully.');
    }
}