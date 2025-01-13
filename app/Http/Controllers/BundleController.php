<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\StaffLog;
use Illuminate\Support\Facades\Auth;

class BundleController extends Controller
{
    public function index()
    {
        $bundles = Bundle::all();
        return view('bundles.index', compact('bundles'));
    }

    public function create()
    {
        return view('bundles.create');
    }

    public function store(Request $request)
{
    try {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Add validation for the image
        ]);

        // Handle the file upload and store it in the public directory
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Save to the public folder (e.g., public/images/bundles)
            $imagePath = $request->file('image')->storeAs('images/bundles', $request->file('image')->getClientOriginalName(), 'public');
        }

        // Create new bundle
        $bundle = new Bundle();
        $bundle->name = $validated['name'];
        $bundle->desc = $validated['desc'];
        $bundle->image = $imagePath;
        $bundle->save();

        // Log the action
        $this->logAction('create', "Created a new bundle: {$bundle->name}");

        return redirect()->route('dashboard')->with('success', 'Bundle created successfully.');
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Catch validation errors and return them to the view
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        // Catch general errors
        \Log::error('Error creating bundle: ' . $e->getMessage());

        return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
    }
}

    

    public function edit(Bundle $bundle)
    {
        return view('bundles.edit', compact('bundle'));
    }

    public function update(Request $request, Bundle $bundle)
{
    try {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Make image nullable
        ]);

        // Handle the image upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($bundle->image) {
                Storage::delete($bundle->image);
            }

            // Store the new image
            $imagePath = $request->file('image')->store('images');
        } else {
            // Keep the existing image if no new image is uploaded
            $imagePath = $bundle->image;
        }

        // Update the bundle
        $bundle->update([
            'name' => $request->name,
            'desc' => $request->desc,
            'image' => $imagePath
        ]);

        // Log the action
        $this->logAction('update', "Updated the bundle: {$bundle->name}");

        // Redirect back to the dashboard with a success message
        return redirect()->route('dashboard')->with('success', 'Bundle updated successfully.');

    } catch (\Exception $e) {
        // Log the exact error message
        \Log::error('Error updating bundle: ' . $e->getMessage());

        // Redirect back to the edit form with the error message and old input
        return redirect()->route('bundle.edit', $bundle->id)
                         ->with('error', 'An error occurred while updating the bundle: ' . $e->getMessage())
                         ->withInput();  // Retain the old input in the form
    }
}

    public function destroy(Bundle $bundle)
    {
        // Delete the image if it exists
        if ($bundle->image) {
            Storage::delete($bundle->image);
        }

        $bundle->delete();
         // Log the action
         $this->logAction('delete', "Deleted the bundle: {$bundleName}");
        return redirect()->route('dashboard');
    }
    public function archive(Bundle $bundle)
{
    $bundle->is_archived = true;
    $bundle->save();
    // Log the action
    $this->logAction('archive', "Archived the bundle: {$bundle->name}");
    return redirect()->route('dashboard')->with('success', 'Bundle archived successfully.');
}

public function restore(Bundle $bundle)
{
    $bundle->is_archived = false;
    $bundle->save();
 // Log the action
     $this->logAction('restore', "Restored the bundle: {$bundle->name}");
    return redirect()->route('dashboard')->with('success', 'Bundle restored successfully.');
}
private function logAction($action, $description)
{
    StaffLog::create([
        'user_id' => Auth::id(), // Assuming you are using Laravel's Auth system
        'action' => $action,
        'description' => $description,
    ]);
}

}
