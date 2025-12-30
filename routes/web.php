<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AuthController; // <--- JANGAN LUPA INI DI ATAS

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// ====================================================
//  👇 LOGIN ROUTE
// ====================================================
Route::get('/login', function () {
    return view('login');
})->name('login');


// ====================================================
// GROUP 1: Route untuk USER (Penyewa) - Wajib Login
// ====================================================
Route::middleware(['auth'])->group(function () {
    
    // Halaman Form Booking
    Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
    
    // Proses Simpan Booking ke Database
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    
    // Halaman Riwayat Booking
    Route::get('/riwayat', [BookingController::class, 'history'])->name('booking.history');

    // 👇 TAMBAHAN BARU: Route untuk Upload Bukti Bayar
    Route::post('/booking/{id}/upload', [BookingController::class, 'uploadProof'])->name('booking.upload');
});

// ====================================================
// GROUP 2: Route khusus STAFF (Operator) - Wajib Login & Role Staff
// ====================================================
Route::middleware(['auth', 'is_staff'])->prefix('staff')->group(function () {
    
    // Dashboard Staff (Lihat jadwal & status)
    Route::get('/dashboard', [StaffController::class, 'index'])->name('staff.dashboard');
    
    // Proses Update Status (Terima / Tolak / Selesai)
    Route::post('/booking/{id}/update', [StaffController::class, 'updateStatus'])->name('staff.booking.update');
});

// ====================================================
// GROUP 3: JALAN TIKUS (SHORTCUT UNTUK TESTING)
// ====================================================

// 1. Link Login Otomatis jadi STAFF
Route::get('/test-login-staff', function () {
    Auth::loginUsingId(1); // ID 1 = Staff
    return redirect()->route('staff.dashboard');
});

// 2. Link Login Otomatis jadi USER
Route::get('/test-login-user', function () {
    Auth::loginUsingId(2); // ID 2 = User Biasa
    return redirect()->route('booking.create');
});

// 3. Link Logout
Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');


});

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'processLogin']);

// Register
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'processRegister']);

// Logout
Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');