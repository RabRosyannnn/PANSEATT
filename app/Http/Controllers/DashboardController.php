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
    $users = User::all();
    $bundles = Bundle::all();
    return view('dashboard', compact('bundles', 'users')); // Corrected compact usage
}

}