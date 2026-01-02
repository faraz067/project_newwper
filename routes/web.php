<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\CourtController;

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
// DASHBOARD REDIRECT (Logic Lalu Lintas)
// =======================
Route::get('/dashboard', function () {
    $user = Auth::user();

    // 1. Jika ADMIN -> Arahkan ke Dashboard Admin
    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }

    // 2. Jika STAFF -> Arahkan ke Dashboard Staff
    if ($user->hasRole('staff')) {
        return redirect()->route('staff.dashboard');
    }

    // 3. Jika USER BIASA -> Tampilkan Dashboard default / User
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

    Route::post('/booking/{id}/charge', [StaffController::class, 'addCharge']) 
        ->name('staff.booking.charge');
});


// =======================
// ADMIN (FULL LENGKAP)
// =======================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    
    // 1. Dashboard Admin
    Route::get('/dashboard', [AdminController::class, 'index'])
        ->name('admin.dashboard');

    // 2. CRUD Lapangan (Courts)
    Route::resource('courts', CourtController::class)
        ->names('admin.courts');

    // 3. Manajemen Booking (INI YANG BARU DITAMBAHKAN)
    // List Booking
    Route::get('/bookings', [AdminController::class, 'bookings'])
        ->name('admin.bookings.index');
    
    // Action Approve
    Route::put('/bookings/{id}/approve', [AdminController::class, 'approveBooking'])
        ->name('admin.bookings.approve');

    // Action Reject
    Route::put('/bookings/{id}/reject', [AdminController::class, 'rejectBooking'])
        ->name('admin.bookings.reject');

});


// =======================
// AUTH (BREEZE)
// =======================
require __DIR__.'/auth.php';