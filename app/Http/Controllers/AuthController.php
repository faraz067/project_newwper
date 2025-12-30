<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // 1. Tampilkan Form Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // 2. Proses Login
    public function processLogin(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba Login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Cek Role: Kalau Staff ke Dashboard, Kalau User ke Booking
            if (Auth::user()->role == 'staff') {
                return redirect()->intended(route('staff.dashboard'));
            }

            return redirect()->intended(route('booking.create'));
        }

        // Kalau Gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // 3. Tampilkan Form Register
    public function showRegister()
    {
        return view('auth.register');
    }

    // 4. Proses Register (Daftar Akun Baru)
    public function processRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed', // butuh input password_confirmation
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default daftar jadi USER biasa
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // 5. Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}