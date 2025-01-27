<?php

namespace App\Http\Controllers;

use App\Models\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Validator;

class RequestController extends Controller
{
    // Method to create a new request
    public function store(HttpRequest $request)
{
    $validatedData = $request->validate([
        'tracking_id' => 'required|exists:reservations,id', // Changed from 'tracking_id'
        'message' => 'required|string',
        'action' => 'required|in:change,cancel',
    ]);

    $newRequest = new Request(); 
    $newRequest->tracking_id = $validatedData['tracking_id'];
    $newRequest->message = $validatedData['message'];
    $newRequest->action = $validatedData['action'];
    $newRequest->save();

    session()->flash('success', 'Your request has been submitted successfully!');

    return redirect()->back();
}

    public function update(HttpRequest $request, $id)
    {
        // Find the request by ID
        $existingRequest = Request::findOrFail($id);

        // Validate the incoming request
        $validatedData = $request->validate([
            'message' => 'sometimes|required|string',
            'action' => 'sometimes|required|in:change,cancel', // Validate action field
        ]);

        // Update the request with validated data
        $existingRequest->update($validatedData);

        return response()->json($existingRequest, 200);
    }
}
