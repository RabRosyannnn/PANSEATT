<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    // Show the create staff form
    public function create()
    {
        return view('register'); // Return the registration view
    }

    // Store a new staff member
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'position' => 'required|string|max:255', // Validate the position field
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'position' => $request->position,
        ]);

        return redirect()->route('dashboard')->with('success', 'Staff member created successfully!');
    }

    // Show the login form
    public function login()
    {
        return view('login'); // Return the login view
    }

    // Authenticate user
    public function authenticate(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($request->only('email', 'password'))) {
        return redirect()->route('dashboard')->with('success', 'Welcome back!');
    }

    return back()->with('error', 'Invalid email or password.')->withInput();
}


    // Logout user
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'You have been logged out.');
    }

    // Edit staff member
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('staff.edit', compact('user'));  // Edit view with user data
    }

    // Update staff member
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id, // Ignore unique validation for current user
            'position' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'position' => $request->position,
        ]);

        return redirect()->route('dashboard')->with('success', 'Staff member updated successfully!');
    }

    // Delete staff member
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('dashboard')->with('success', 'Staff member deleted successfully!');
    }
    public function archive(User $staff)
{
    $staff->is_archived = true;
    $staff->save();

    return redirect()->route('dashboard')->with('success', 'Staff member archived successfully.');
}

public function restore(User $staff)
{
    $staff->is_archived = false;
    $staff->save();

    return redirect()->route('dashboard')->with('success', 'Staff member restored successfully.');
}

}
