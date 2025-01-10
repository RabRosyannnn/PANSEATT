<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BundleController;


// Staff routes
Route::prefix('staff')->middleware('auth')->group(function () {
    Route::get('/', [StaffController::class, 'index'])->name('staff.index');  // Show all staff
    Route::get('create', [StaffController::class, 'create'])->name('staff.create');  // Show create form
    Route::post('store', [StaffController::class, 'store'])->name('staff.store');  // Store new staff
    Route::get('edit/{id}', [StaffController::class, 'edit'])->name('staff.edit');  // Edit staff form
    Route::put('update/{id}', [StaffController::class, 'update'])->name('staff.update');  // Update staff
    Route::delete('destroy/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');  // Delete staff
});

Route::resource('bundles', BundleController::class);

// Home Route
Route::get('/', function () {
    return view('welcome');
});

// Grouping routes with 'auth' middleware
Route::middleware(['auth'])->group(function () {
    // Dashboard Route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Logout Route
    Route::post('logout', [StaffController::class, 'logout'])->name('logout');
    
    Route::resource('bundles', BundleController::class);
    Route::get('/bundles/{bundle}/edit', [BundleController::class, 'edit'])->name('bundles.edit');
    Route::put('/bundles/{bundle}', [BundleController::class, 'update'])->name('bundles.update');
    Route::post('/bundles', [BundleController::class, 'store'])->name('bundles.store');
    Route::post('/bundles/{bundle}/archive', [BundleController::class, 'archive'])->name('bundles.archive');
    Route::post('/bundles/{bundle}/restore', [BundleController::class, 'restore'])->name('bundles.restore');

    Route::post('/staff/{user}/archive', [StaffController::class, 'archive'])->name('staff.archive');


});

// Registration Routes (no middleware, accessible to everyone)
Route::get('register', [StaffController::class, 'create'])->name('register');
Route::post('register', [StaffController::class, 'store']);

// Login Routes (no middleware, accessible to everyone)
Route::get('login', [StaffController::class, 'login'])->name('login');
Route::post('login', [StaffController::class, 'authenticate'])->name('login.authenticate');