<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StaffLog;
use App\Models\Bundle;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    $activeStaff = User::where('is_archived', false)->get();
    $archivedStaff = User::where('is_archived', true)->get();
    $activeBundles = Bundle::where('is_archived', false)->get();
    $archivedBundles = Bundle::where('is_archived', true)->get();
    return view('dashboard', compact('activeBundles', 'archivedBundles', 'activeStaff', 'archivedStaff')); // Corrected compact usage
}

}