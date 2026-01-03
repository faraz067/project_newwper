<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\CourtController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\Booking; 
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =======================
// 1. PUBLIC ROUTES
// =======================
Route::get('/', function () {
    return view('home'); 
})->name('home');

Route::get('/lapangan', fn () => view('lapangan'))->name('lapangan');
Route::get('/jadwal', fn () => view('jadwal'))->name('jadwal');
Route::get('/cara-booking', fn () => view('cara-booking'))->name('cara-booking');


// =======================
// 2. DASHBOARD REDIRECT & LOGIC (User, Staff, Admin)
// =======================
Route::get('/dashboard', function () {
    $user = Auth::user();

    // A. Redirect Admin
    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }

    // B. Redirect Staff
    if ($user->hasRole('staff')) {
        return redirect()->route('staff.dashboard');
    }

    // C. Logic Dashboard User Biasa
    $userId = $user->id;

    $activeBookings = Booking::where('user_id', $userId)
        ->whereNotIn('status', ['completed', 'cancelled', 'rejected'])
        ->orderBy('start_time', 'asc')
        ->get();

    $historyBookings = Booking::where('user_id', $userId)
        ->whereIn('status', ['completed', 'cancelled', 'rejected'])
        ->orderBy('start_time', 'desc')
        ->take(5)
        ->get();

    $totalMain = Booking::where('user_id', $userId)
        ->where('status', 'completed')
        ->count();

    return view('dashboard', compact('activeBookings', 'historyBookings', 'totalMain'));

})->middleware(['auth', 'verified'])->name('dashboard');


// =======================
// 3. PROFILE (BREEZE - FIX METHOD)
// =======================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    
    // Pastikan di Blade menggunakan @method('PATCH')
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// =======================
// 4. KHUSUS ROLE USER (Booking)
// =======================
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/riwayat', [BookingController::class, 'history'])->name('booking.history');
    Route::post('/booking/{id}/upload', [BookingController::class, 'uploadProof'])->name('booking.upload');
});


// =======================
// 5. KHUSUS ROLE STAFF (FIXED & NAMED)
// =======================
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    
    // Dashboard Staff: route('staff.dashboard')
    Route::get('/dashboard', [StaffController::class, 'index'])->name('dashboard');

    // Update Status: route('staff.booking.update')
    Route::post('/booking/{id}/update', [StaffController::class, 'updateStatus'])->name('booking.update');

    // Tambah Biaya: route('staff.booking.charge')
    Route::post('/booking/{id}/charge', [StaffController::class, 'charge'])->name('booking.charge');
});


// =======================
// 6. KHUSUS ROLE ADMIN
// =======================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin: route('admin.dashboard')
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // CRUD Lapangan: route('admin.courts.index', dll)
    Route::resource('courts', CourtController::class);

    // Manajemen Booking
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings.index');
    Route::put('/bookings/{id}/approve', [AdminController::class, 'approveBooking'])->name('bookings.approve');
    Route::put('/bookings/{id}/reject', [AdminController::class, 'rejectBooking'])->name('bookings.reject');
});


// =======================
// 7. AUTH & LOGOUT FIX
// =======================
Route::get('/logout-manual', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});

require __DIR__.'/auth.php';