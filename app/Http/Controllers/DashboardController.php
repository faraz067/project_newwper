<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking; // Pastikan model Booking sudah ada
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. Data Booking Aktif (Upcoming)
        $activeBookings = Booking::with('field') // Pastikan ada relasi 'field' di model Booking
            ->where('user_id', $userId)
            ->where('start_time', '>=', Carbon::now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_time', 'asc')
            ->get();

        // 2. Data Riwayat (History)
        $historyBookings = Booking::with('field')
            ->where('user_id', $userId)
            ->where('start_time', '<', Carbon::now())
            ->orderBy('start_time', 'desc')
            ->take(5)
            ->get();

        // 3. Total Main
        $totalMain = $historyBookings->count(); 
        // Atau kalau mau hitung semua database:
        // $totalMain = Booking::where('user_id', $userId)->where('start_time', '<', Carbon::now())->count();

        return view('dashboard', compact('activeBookings', 'historyBookings', 'totalMain'));
    }
}