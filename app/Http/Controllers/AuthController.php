<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking; 
use App\Models\Field;   
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // 1. PENDAPATAN HARI INI
        $todaysIncome = Booking::whereDate('created_at', Carbon::today())
            ->where('status', 'paid') // Pastikan status sesuai database kamu
            ->sum('total_price');

        // 2. USER BARU
        $newUsers = User::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // 3. BOOKING AKTIF
        $activeBookings = Booking::whereIn('status', ['pending', 'confirmed', 'paid']) 
            ->count();

        // 4. TOTAL SALES
        $totalSales = Booking::where('status', 'paid')->sum('total_price');

        // 5. DATA LAPANGAN
        $fields = Field::all();

        return view('dashboard', compact(
            'todaysIncome', 
            'newUsers', 
            'activeBookings', 
            'totalSales', 
            'fields'
        ));
    }
}