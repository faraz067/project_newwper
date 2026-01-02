<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\Auth\LoginController;

// MODEL & HELPER (Untuk Logika Dashboard)
use App\Models\Booking; 
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =======================
// 1. PUBLIC ROUTES (Bisa diakses siapa saja)
// =======================
Route::get('/', function () {
    return view('home'); 
})->name('home');

Route::get('/lapangan', fn () => view('lapangan'));
Route::get('/jadwal', fn () => view('jadwal'));


// =======================
// 2. AUTHENTICATED ROUTES (Harus Login Dulu)
// =======================
Route::middleware(['auth', 'verified'])->group(function () {

    // --- DASHBOARD USER (Logika Statistik & Riwayat) ---
    Route::get('/dashboard', function () {
        $userId = Auth::id();

        // A. Booking Aktif (Akan Datang / Sedang Berlangsung)
        // UPDATE: Filter berdasarkan status.
        // Tampilkan jika status BUKAN 'completed', 'cancelled', atau 'rejected'.
        $activeBookings = Booking::where('user_id', $userId)
            ->whereNotIn('status', ['completed', 'cancelled', 'rejected'])
            ->orderBy('start_time', 'asc')
            ->get();

        // B. Riwayat Booking (Sudah Selesai / Batal)
        // UPDATE: Tampilkan jika status SUDAH 'completed', 'cancelled', atau 'rejected'.
        $historyBookings = Booking::where('user_id', $userId)
            ->whereIn('status', ['completed', 'cancelled', 'rejected'])
            ->orderBy('start_time', 'desc')
            ->take(5)
            ->get();

        // C. Statistik Total Main
        // UPDATE: Hitung hanya yang statusnya benar-benar 'completed'
        $totalMain = Booking::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        return view('dashboard', compact('activeBookings', 'historyBookings', 'totalMain'));
    })->name('dashboard');


    // --- PROFIL USER (Edit Foto, Alamat, Password) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});


// =======================
// 3. KHUSUS ROLE USER (Booking Lapangan)
// =======================
Route::middleware(['auth', 'role:user'])->group(function () {
    
    // Form Booking & Simpan
    Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    
    // Riwayat & Upload Bukti
    Route::get('/riwayat', [BookingController::class, 'history'])->name('booking.history');
    Route::post('/booking/{id}/upload', [BookingController::class, 'uploadProof'])->name('booking.upload');

});


// =======================
// 4. KHUSUS ROLE STAFF
// =======================
Route::middleware(['auth', 'role:staff'])->prefix('staff')->group(function () {
    // Dashboard Staff
    Route::get('/dashboard', [StaffController::class, 'index'])->name('staff.dashboard');
    
    // Update Status Booking (Approve/Reject/Selesai)
    Route::post('/booking/{id}/update', [StaffController::class, 'updateStatus'])->name('staff.booking.update');

    // Input Biaya Tambahan (Charge)
    Route::post('/booking/{id}/charge', [StaffController::class, 'charge'])->name('staff.booking.charge');
});


// =======================
// 5. KHUSUS ROLE ADMIN
// =======================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return 'INI DASHBOARD ADMIN'; 
    })->name('admin.dashboard');
});


// =======================
// 6. LOGOUT FIX
// =======================
Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); 

Route::get('/logout-manual', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});

// =======================
// AUTH (Breeze/Laravel UI)
// =======================
require __DIR__.'/auth.php';