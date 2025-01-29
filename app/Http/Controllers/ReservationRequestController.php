<?php

namespace App\Http\Controllers;

use App\Models\ReservationRequest;
use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Http; // Ensure Http facade is imported
use Illuminate\Support\Facades\Log;

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

    public function approve(Request $request, $id)
{
    // Step 1: Find the ReservationRequest by its ID
    $reservationRequest = ReservationRequest::find($id);

    if (!$reservationRequest) {
        return redirect()->route('dashboard')->with('error', 'Request not found!');
    }

    // Step 2: Retrieve the tracking_id from the reservation request
    $trackingId = $reservationRequest->tracking_id;

    // Step 3: Find the associated reservation by tracking_id
    $reservation = Reservation::where('tracking_id', $trackingId)->first();

    // Step 4: Check if the reservation exists and retrieve contact_information
    if ($reservation) {
        $contactInformation = $reservation->contact_information;

        // Step 5: Transform the contact number (only replacing the leading '0' with '+63')
        $contactInformation = $this->transformContactNumber($contactInformation);

        Log::info('Transformed Contact Information: ' . $contactInformation);

        // Step 6: Get the message from the request
        $adminMessage = $request->input('message');
        $defaultMessage = " \nYour reservation request has been approved. Thank you!";
        $message = $adminMessage . $defaultMessage;

        // Send SMS
        $response = Http::withHeaders([
            'Authorization' => 'd1f02324-c1fa-4490-9059-c2860ac5df6d'
        ])->post('http://192.168.1.17:8082/sms-endpoint', [
            'to' => $contactInformation,
            'message' => $message
        ]);

        // Step 7: Delete the request after sending SMS
        $reservationRequest->delete();

        return redirect()->route('dashboard')->with('success', 'Request approved, user notified, and request deleted!');
    } else {
        return redirect()->route('dashboard')->with('error', 'No reservation found for this request!');
    }
}

public function reject(Request $request, $id)
{
    // Step 1: Find the ReservationRequest by its ID
    $reservationRequest = ReservationRequest::find($id);

    if (!$reservationRequest) {
        return redirect()->route('dashboard')->with('error', 'Request not found!');
    }

    // Step 2: Retrieve the tracking_id from the reservation request
    $trackingId = $reservationRequest->tracking_id;

    // Step 3: Find the associated reservation by tracking_id
    $reservation = Reservation::where('tracking_id', $trackingId)->first();

    // Step 4: Check if the reservation exists and retrieve contact_information
    if ($reservation) {
        $contactInformation = $reservation->contact_information;

        // Step 5: Transform the contact number (only replacing the leading '0' with '+63')
        $contactInformation = $this->transformContactNumber($contactInformation);

        Log::info('Transformed Contact Information: ' . $contactInformation);

        // Step 6: Get the message from the request
        $adminMessage = $request->input('message');
        $defaultMessage = "\n Your reservation request has been rejected. Please contact support for more details.";
        $message = $adminMessage . $defaultMessage;

        // Send SMS
        $response = Http::withHeaders([
            'Authorization' => 'd1f02324-c1fa-4490-9059-c2860ac5df6d'
        ])->post('http://192.168.1.17:8082/sms-endpoint', [
            'to' => $contactInformation,
            'message' => $message
        ]);

        // Step 7: Delete the request after sending SMS
        $reservationRequest->delete();

        return redirect()->route('dashboard')->with('error', 'Request rejected, user notified, and request deleted!');
    } else {
        return redirect()->route('dashboard')->with('error', 'No reservation found for this request!');
    }
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
