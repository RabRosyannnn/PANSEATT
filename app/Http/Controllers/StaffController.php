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
        'name' => 'required|string|max:255|regex:/^[A-Za-z]+$/', // Only letters, no spaces or special characters
        'email' => 'required|string|email|max:255|unique:users|regex:/\S/', // Must not be empty or only spaces
        'password' => 'required|string|min:8|regex:/\S/', // Must not be empty or only spaces
        'position' => 'required|string|max:255', // Validate the position field
    ], [
        'name.regex' => 'The name must only contain letters (no spaces, numbers, or special characters).',
        'name.required' => 'The name field is required.',
        'email.regex' => 'The email must not contain only spaces.',
        'password.regex' => 'The password must not contain only spaces.',
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
    public function archive($id)
{
    // Retrieve the user by ID
    $user = User::findOrFail($id); // This will throw a 404 error if the user is not found

    // Set the is_archived property to true
    $user->is_archived = true;
    $user->save();

    return redirect()->route('dashboard')->with('success', 'Staff member archived successfully.');
}

public function restore($id)
{
    $user = User::findOrFail($id);
    $user->is_archived = false;
    $user->save();

    return redirect()->route('dashboard')->with('success', 'Staff member restored successfully.');
}

}
