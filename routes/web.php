<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BundleController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\RequestController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReservationRequestController;

use App\Http\Controllers\SmsController;
Route::post('/requests', [ReservationRequestController::class, 'store'])->name('requests.store');
Route::get('/admin/requests', [ReservationRequestController::class, 'index'])->name('admin.requests.index');
Route::patch('/admin/requests/{reservationRequest}', [ReservationRequestController::class, 'update'])->name('admin.requests.update');
// routes/web.php
Route::post('/sms-endpoint', [SmsController::class, 'sendSms'])->withoutMiddleware('web');

// Staff routes
Route::prefix('staff')->middleware('auth')->group(function () {
    Route::get('/', [StaffController::class, 'index'])->name('staff.index');  // Show all staff
    Route::get('create', [StaffController::class, 'create'])->name('staff.create');  // Show create form
    Route::post('store', [StaffController::class, 'store'])->name('staff.store');  // Store new staff
    Route::get('edit/{id}', [StaffController::class, 'edit'])->name('staff.edit');  // Edit staff form
    Route::put('update/{user}', [StaffController::class, 'update'])->name('staff.update');  // Update staff
    Route::delete('destroy/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');  // Delete staff
});

Route::resource('bundles', BundleController::class);

// Home Route
Route::get('/panseat-tagapo', [StorefrontController::class, 'index'])->name('home'); 

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
    Route::post('/staff/{user}/restore', [StaffController::class, 'restore'])->name('staff.restore');
    Route::get('/staff/{id}', 'StaffController@show')->name('staff.show');
    
    Route::delete('/staff/{id}', 'StaffController@destroy')->name('staff.destroy');

    Route::resource('reservations', ReservationController::class);
    Route::get('/events', [DashboardController::class, 'getEvents']);
    Route::get('/reservations/data', [ReservationController::class, 'getReservations'])->name('reservations.data');

    Route::get('/events', [ReservationController::class, 'getEvents'])->name('events.get');
    Route::get('/reservations/{id}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::post('/generate-report', [ReportController::class, 'generate'])->name('generate.report');

});

// Registration Routes (no middleware, accessible to everyone)
Route::get('register', [StaffController::class, 'create'])->name('register');
Route::post('register', [StaffController::class, 'store']);
Route::post('register', [StaffController::class, 'store2'])->name('register2') ->middleware('guest');
// Login Routes (no middleware, accessible to everyone)
Route::get('login', [StaffController::class, 'login'])
    ->name('login')
    ->middleware('guest'); // Redirects authenticated users

Route::post('login', [StaffController::class, 'authenticate'])
    ->name('login.authenticate')
    ->middleware('guest');
Route::get('/track-reservation/{id}', [ReservationController::class, 'customerShow'])
     ->name('reservations.customerShow');
Route::post('/reservations/track', [ReservationController::class, 'trackReservation'])
     ->name('reservations.track');


// Route to update an existing request
Route::put('/requests/{id}', [RequestController::class, 'update'])->name('requests.update');
Route::patch('/requests/{id}/approve', [ReservationRequestController::class, 'approve'])->name('requests.approve');
Route::patch('/requests/{id}/reject', [ReservationRequestController::class, 'reject'])->name('requests.reject');
Route::post('/reservations/checkConflict', [ReservationController::class, 'checkConflict'])->name('reservations.checkConflict');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/busy-days', [BusyDayController::class, 'index']);
    Route::post('/busy-days', [BusyDayController::class, 'store']);
    Route::delete('/busy-days/{date}', [BusyDayController::class, 'destroy']);
    Route::get('/busy-days/check', [BusyDayController::class, 'check']);
});
