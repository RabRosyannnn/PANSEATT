<?php

namespace App\Http\Controllers;

use App\Models\BusyDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BusyDayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->except(['index']); // Ensure only admins can modify
    }

    public function index()
    {
        $busyDays = BusyDay::orderBy('date')
            ->get()
            ->map(function($day) {
                return [
                    'date' => $day->date->format('Y-m-d'),
                    'reason' => $day->reason
                ];
            });

        return response()->json($busyDays);
    }

    public function store(Request $request)
    {
        $request->validate([
            'dates' => 'required|array',
            'dates.*' => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'reason' => 'nullable|string|max:255'
        ]);

        $created = [];
        $errors = [];

        foreach ($request->dates as $date) {
            try {
                BusyDay::create([
                    'date' => $date,
                    'reason' => $request->reason,
                    'created_by' => Auth::id()
                ]);
                $created[] = $date;
            } catch (\Exception $e) {
                $errors[] = $date;
            }
        }

        return response()->json([
            'message' => 'Busy days processed',
            'created' => $created,
            'errors' => $errors
        ]);
    }
    public function destroy($date)
    {
        $busyDay = BusyDay::whereDate('date', $date)->first();
        
        if (!$busyDay) {
            return response()->json(['message' => 'Date not found'], 404);
        }

        $busyDay->delete();

        return response()->json(['message' => 'Date removed successfully']);
    }

    public function check(Request $request)
    {
        $request->validate([
            'date' => 'required|date|date_format:Y-m-d'
        ]);

        $isBusy = BusyDay::whereDate('date', $request->date)->exists();

        return response()->json([
            'date' => $request->date,
            'is_busy' => $isBusy
        ]);
    }
}