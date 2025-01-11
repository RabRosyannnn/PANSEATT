<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StaffLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    // Show all staff members
    public function index()
    {
        $activeStaff = User::where('is_archived', false)->get();
        $archivedStaff = User::where('is_archived', true)->get();
        $staffLogs = StaffLog::orderBy('created_at', 'desc')->get();
        
        return view('staff.index', compact('activeStaff', 'archivedStaff', 'staffLogs'));
    }

    // Show the create staff form
    public function create()
    {
        return view('register');
    }

    // Store a new staff member
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^[A-Za-z]+$/',
            'email' => 'required|string|email|max:255|unique:users|regex:/\S/',
            'password' => 'required|string|min:8|regex:/\S/',
            'position' => 'required|string|max:255',
        ], [
            'name.regex' => 'The name must only contain letters (no spaces, numbers, or special characters).',
            'name.required' => 'The name field is required.',
            'email.regex' => 'The email must not contain only spaces.',
            'password.regex' => 'The password must not contain only spaces.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'position' => $request->position,
            'is_archived' => false,
        ]);

        // Create staff log entry
        StaffLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'details' => "Created new staff member: {$user->name}",
        ]);

        return redirect()->route('dashboard')->with('success', 'Staff member created successfully!');
    }

    // Show staff member details
    public function show($id)
    {
        $staff = User::findOrFail($id);
        $staffLogs = StaffLog::where('user_id', $id)->orderBy('created_at', 'desc')->get();
        
        return view('staff.show', compact('staff', 'staffLogs'));
    }

    // Edit staff member
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('staff.edit', compact('user'));
    }

    // Update staff member
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'position' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($id);
        $oldData = $user->toArray();
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'position' => $request->position,
        ]);

        // Create staff log entry for the update
        StaffLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'details' => "Updated staff member: {$user->name}",
        ]);

        return redirect()->route('dashboard')->with('success', 'Staff member updated successfully!');
    }

    // Archive staff member
    public function archive($id)
    {
        $user = User::findOrFail($id);
        $user->is_archived = true;
        $user->save();

        // Create staff log entry
        StaffLog::create([
            'user_id' => Auth::id(),
            'action' => 'archive',
            'details' => "Archived staff member: {$user->name}",
        ]);

        return redirect()->route('dashboard')->with('success', 'Staff member archived successfully.');
    }

    // Restore staff member
    public function restore($id)
    {
        $user = User::findOrFail($id);
        $user->is_archived = false;
        $user->save();

        // Create staff log entry
        StaffLog::create([
            'user_id' => Auth::id(),
            'action' => 'restore',
            'details' => "Restored staff member: {$user->name}",
        ]);

        return redirect()->route('dashboard')->with('success', 'Staff member restored successfully.');
    }

    // Delete staff member
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $userName = $user->name;
        $user->delete();

        // Create staff log entry
        StaffLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete',
            'details' => "Deleted staff member: {$userName}",
        ]);

        return redirect()->route('dashboard')->with('success', 'Staff member deleted successfully!');
    }

    // Authentication methods
    public function login()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            // Create staff log entry for login
            StaffLog::create([
                'user_id' => Auth::id(),
                'action' => 'login',
                'details' => Auth::user()->name . ' logged in',
            ]);
            
            return redirect()->route('dashboard')->with('success', 'Welcome back!');
        }

        return back()->with('error', 'Invalid email or password.')->withInput();
    }

    public function logout()
    {
        // Create staff log entry before logging out
        if (Auth::check()) {
            StaffLog::create([
                'user_id' => Auth::id(),
                'action' => 'logout',
                'details' => Auth::user()->name . ' logged out',
            ]);
        }

        Auth::logout();
        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}