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
        $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Add validation for the image
        ]);
    
        // Handle the file upload and store it in the public directory
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Save to the public folder (e.g., public/images/bundles)
            $imagePath = $request->file('image')->storeAs('images/bundles', $request->file('image')->getClientOriginalName(), 'public');
        }
    
        // Create new bundle
        $bundle = new Bundle();
        $bundle->name = $request->name;
        $bundle->desc = $request->desc;
        $bundle->image = $imagePath;
        $bundle->save();
         // Log the action
         $this->logAction('create', "Created a new bundle: {$bundle->name}");
        return redirect()->route('dashboard')->with('success', 'Bundle created successfully.');
    }
    

    public function edit(Bundle $bundle)
    {
        return view('bundles.edit', compact('bundle'));
    }

    public function update(Request $request, Bundle $bundle)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('image') ? $request->file('image')->store('images') : $bundle->image;

        $bundle->update([
            'name' => $request->name,
            'desc' => $request->desc,
            'image' => $imagePath
        ]);
         // Log the action
         $this->logAction('update', "Updated the bundle: {$bundle->name}");
        return redirect()->route('dashboard');
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
