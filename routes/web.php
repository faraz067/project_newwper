<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\StaffController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =======================
// PUBLIC
// =======================
Route::get('/', function () {
    return view('home');
});

Route::get('/lapangan', fn () => view('lapangan'));
Route::get('/jadwal', fn () => view('jadwal'));
Route::get('/cara-booking', fn () => view('cara-booking'));


// =======================
// DASHBOARD DEFAULT
// =======================
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// =======================
// PROFILE (BREEZE)
// =======================
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});


// =======================
// BOOKING (USER)
// =======================
Route::middleware(['auth', 'role:user'])->group(function () {

    Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/riwayat', [BookingController::class, 'history'])->name('booking.history');

    Route::post('/booking/{id}/upload', [BookingController::class, 'uploadProof'])
        ->name('booking.upload');
});


// =======================
// STAFF
// =======================
Route::middleware(['auth', 'role:staff'])->prefix('staff')->group(function () {

    Route::get('/dashboard', [StaffController::class, 'index'])
        ->name('staff.dashboard');

    Route::post('/booking/{id}/update', [StaffController::class, 'updateStatus'])
        ->name('staff.booking.update');
});


// =======================
// ADMIN
// =======================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    Route::get('/dashboard', function () {
        return 'INI DASHBOARD ADMIN';
    })->name('admin.dashboard');

});


// =======================
// AUTH (BREEZE)
// =======================
require __DIR__.'/auth.php';
