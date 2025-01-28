<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\StaffLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle the file upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $uniqueName = time() . '_' . $request->file('image')->getClientOriginalName();
            $imagePath = $request->file('image')->storeAs('images/bundles', $uniqueName, 'public');
        }

        // Save the bundle
        $bundle = new Bundle();
        $bundle->name = $validated['name'];
        $bundle->desc = $validated['desc'];
        $bundle->price = $validated['price'];
        $bundle->image = $imagePath;
        $bundle->save();

        $this->logAction('create', "Created a new bundle: {$bundle->name}");

        return response()->json([
            'success' => true,
            'message' => 'Bundle added successfully.',
            'bundle' => array_merge($bundle->toArray(), [
                'image_url' => asset('storage/' . $bundle->image),
            ]),
        ]);
    } catch (ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Error adding bundle: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while adding the bundle.',
        ], 500);
    }
}

    

    public function edit(Bundle $bundle)
    {
        return view('bundles.edit', compact('bundle'));
    }

    public function update(Request $request, Bundle $bundle)
{
    $request->validate([
        'image' => 'nullable|image|max:1024', // Validation for the image
        'name' => 'required|string',
        'desc' => 'required|string',
        'price' => 'required|numeric',
    ]);

    if ($request->hasFile('image')) {
        // Delete the old image if it exists
        if ($bundle->image) {
            Storage::delete($bundle->image);
        }

        // Store the new image and get the path
        $imagePath = $request->file('image')->store('bundles', 'public');
        $bundle->image = $imagePath;
    }

    // Update other fields
    $bundle->name = $request->input('name');
    $bundle->desc = $request->input('desc');
    $bundle->price = $request->input('price');
    $bundle->save();

    // Redirect back to the dashboard with a success message
    return redirect()->route('dashboard')->with('success', 'Bundle updated successfully!');
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
