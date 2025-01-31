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
            'category' => 'required|string',
            'desc' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'The bundle name is required.',
            'category.required' => 'The bundle category is required.',
            'desc.required' => 'The description is required.',
            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a number.',
            'image.required' => 'An image is required.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image may not be greater than 2MB.',
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
        $bundle->category = $validated['category'];
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
        // Return structured validation errors
        return response()->json([
            'success' => false,
            'message' => 'Make sure input fields are correct and image is JPG, PNG, JPEG .',
            'errors' => $e->errors(), // Return the validation errors
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Error adding bundle: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while adding the bundle. Please try again later.',
        ], 500);
    }
}
    

    public function edit(Bundle $bundle)
    {
        return view('bundles.edit', compact('bundle'));
    }

    public function update(Request $request, Bundle $bundle)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'category' => 'required|string',
        'desc' => 'required|string',
        'price' => 'required|numeric',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image is now optional
    ], [
        'name.required' => 'The bundle name is required.',
        'category.required' => 'The bundle category is required.',
        'desc.required' => 'The description is required.',
        'price.required' => 'The price is required.',
        'price.numeric' => 'The price must be a number.',
        'image.image' => 'The file must be an image.',
        'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
        'image.max' => 'The image may not be greater than 2MB.',
    ]);

    // Handle the file upload (only update if a new image is provided)
    if ($request->hasFile('image')) {
        $uniqueName = time() . '_' . $request->file('image')->getClientOriginalName();
        $imagePath = $request->file('image')->storeAs('images/bundles', $uniqueName, 'public');
        $bundle->image = $imagePath; // Update image
    }

    // Update the existing bundle
    $bundle->name = $validated['name'];
    $bundle->desc = $validated['desc'];
    $bundle->price = $validated['price'];
    $bundle->category = $validated['category'];
    $bundle->save(); // Save changes

    // Redirect back with success message
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
