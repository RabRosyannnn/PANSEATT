<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bundle;

class StorefrontController extends Controller
{
    public function index()
    {
        $activeBundles = Bundle::where('is_archived', false)->get();
        return view('storefront', compact('activeBundles'));
    }
}
