<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Tambahan dari teman
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\CourtController;
use App\Http\Controllers\Auth\LoginController; // Tambahan dari teman

// MODEL & HELPER (Penting untuk Dashboard User punya teman)
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

Route::get('/lapangan', fn () => view('lapangan'));
Route::get('/jadwal', fn () => view('jadwal'));
Route::get('/cara-booking', fn () => view('cara-booking'));


// =======================
// 2. DASHBOARD REDIRECT & LOGIC (GABUNGAN)
// =======================
Route::get('/dashboard', function () {
    $user = Auth::user();

    // A. Jika ADMIN -> Arahkan ke Dashboard Admin (Punya Kamu)
    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }

    // B. Jika STAFF -> Arahkan ke Dashboard Staff (Punya Kamu)
    if ($user->hasRole('staff')) {
        return redirect()->route('staff.dashboard');
    }

    // C. Jika USER BIASA -> Jalankan Logic Teman (Query Data)
    // -------------------------------------------------------
    $userId = $user->id;

    // 1. Booking Aktif (Bukan completed/cancelled/rejected)
    $activeBookings = Booking::where('user_id', $userId)
        ->whereNotIn('status', ['completed', 'cancelled', 'rejected'])
        ->orderBy('start_time', 'asc')
        ->get();

    // 2. Riwayat Booking (Completed/Cancelled/Rejected)
    $historyBookings = Booking::where('user_id', $userId)
        ->whereIn('status', ['completed', 'cancelled', 'rejected'])
        ->orderBy('start_time', 'desc')
        ->take(5)
        ->get();

    // 3. Statistik Total Main
    $totalMain = Booking::where('user_id', $userId)
        ->where('status', 'completed')
        ->count();

    // Kirim data ke view dashboard user
    return view('dashboard', compact('activeBookings', 'historyBookings', 'totalMain'));

})->middleware(['auth', 'verified'])->name('dashboard');


// =======================
// 3. PROFILE (BREEZE)
// =======================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update'); // Standar Breeze pakai PATCH
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
// 5. KHUSUS ROLE STAFF
// =======================
Route::middleware(['auth', 'role:staff'])->prefix('staff')->group(function () {
    Route::get('/dashboard', [StaffController::class, 'index'])
        ->name('staff.dashboard');

    Route::post('/booking/{id}/update', [StaffController::class, 'updateStatus'])
        ->name('staff.booking.update');

    // NOTE: Pastikan di StaffController method-nya bernama 'addCharge' (sesuai kodingan kamu)
    // Kalau error, coba ganti jadi 'charge' (sesuai kodingan teman)
    Route::post('/booking/{id}/charge', [StaffController::class, 'addCharge']) 
        ->name('staff.booking.charge');
});


// =======================
// 6. KHUSUS ROLE ADMIN (FULL LENGKAP - VERSI KAMU)
// =======================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', [AdminController::class, 'index'])
        ->name('admin.dashboard');

    // CRUD Lapangan
    Route::resource('courts', CourtController::class)
        ->names('admin.courts');

    // Manajemen Booking (Approve/Reject)
    Route::get('/bookings', [AdminController::class, 'bookings'])
        ->name('admin.bookings.index');
    
    Route::put('/bookings/{id}/approve', [AdminController::class, 'approveBooking'])
        ->name('admin.bookings.approve');

    Route::put('/bookings/{id}/reject', [AdminController::class, 'rejectBooking'])
        ->name('admin.bookings.reject');

});


// =======================
// 7. AUTH & LOGOUT FIX
// =======================
// Tambahan teman buat logout manual (opsional, tapi berguna)
Route::get('/logout-manual', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});

require __DIR__.'/auth.php';