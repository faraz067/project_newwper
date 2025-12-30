<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Depan
Route::get('/', function () {
    return view('welcome');
});

// ====================================================
// GROUP 1: AUTHENTICATION (Login, Register, Logout)
// ====================================================
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'processLogin']);

    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'processRegister']);
});

// Logout (Hanya bisa diakses kalau sudah login)
Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// ====================================================
// GROUP 2: Route untuk USER (Penyewa) - Wajib Login
// ====================================================
Route::middleware(['auth'])->group(function () {
    
    // Halaman Form Booking
    Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
    
    // Proses Simpan Booking ke Database
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    
    // Halaman Riwayat Booking
    Route::get('/riwayat', [BookingController::class, 'history'])->name('booking.history');

    // Route untuk Upload Bukti Bayar
    Route::post('/booking/{id}/upload', [BookingController::class, 'uploadProof'])->name('booking.upload');
});


// ====================================================
// GROUP 3: Route khusus STAFF (Operator) - Wajib Login & Role Staff
// ====================================================
Route::middleware(['auth', 'is_staff'])->prefix('staff')->group(function () {
    
    // Dashboard Staff (Lihat jadwal & status)
    Route::get('/dashboard', [StaffController::class, 'index'])->name('staff.dashboard');
    
    // Proses Update Status (Terima / Tolak / Selesai)
    Route::post('/booking/{id}/update', [StaffController::class, 'updateStatus'])->name('staff.booking.update');

    // 👇 TAMBAHAN BARU: Input Denda / Biaya Tambahan
    Route::post('/booking/{id}/charge', [StaffController::class, 'addCharge'])->name('staff.booking.charge');
});


// ====================================================
// GROUP 4: JALAN TIKUS (SHORTCUT UNTUK TESTING)
// ====================================================

// 1. Link Login Otomatis jadi STAFF
Route::get('/test-login-staff', function () {
    // Pastikan User ID 1 ada di database dan role-nya 'staff'
    if (Auth::loginUsingId(1)) {
        return redirect()->route('staff.dashboard');
    }
    return "User ID 1 tidak ditemukan!";
});

// 2. Link Login Otomatis jadi USER
Route::get('/test-login-user', function () {
    // Pastikan User ID 2 ada di database
    if (Auth::loginUsingId(2)) {
        return redirect()->route('booking.create');
    }
    return "User ID 2 tidak ditemukan!";
});