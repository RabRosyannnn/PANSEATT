<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SmsController extends Controller
{
    public function sendSms(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => 'd1f02324-c1fa-4490-9059-c2860ac5df6d'
        ])->post('http://192.168.1.7:8082/sms-endpoint', [
            'phone' => '1234567890',
            'message' => 'Test message'
        ]);
        // Respond or process as needed
        return response()->json(['status' => 'success']);
    }
}