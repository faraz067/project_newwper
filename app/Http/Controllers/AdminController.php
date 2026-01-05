<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // TAMBAHAN: Wajib ada untuk fungsi raw SQL (MONTH, SUM, COUNT)
use App\Models\User;
use App\Models\Booking; 
use App\Models\Court; // Pastikan ini Court, bukan Field (sesuai database kamu)
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // ==========================================
        // 1. DATA KARTU STATISTIK (BAGIAN ATAS)
        // ==========================================

        // PENDAPATAN HARI INI
        // Kita anggap uang masuk saat statusnya 'confirmed' atau 'completed'
        $todaysIncome = Booking::whereDate('created_at', \Carbon\Carbon::today())
            ->whereIn('status', ['confirmed', 'completed']) 
            ->sum('total_price');

        // USER BARU (Tetap sama)
        $newUsers = User::whereMonth('created_at', \Carbon\Carbon::now()->month)
            ->whereYear('created_at', \Carbon\Carbon::now()->year)
            ->count();

        // BOOKING AKTIF 
        // Yang masih berjalan (Pending waiting list + Confirmed siap main)
        // 'completed' tidak dihitung karena sudah lewat.
        $activeBookings = Booking::whereIn('status', ['pending', 'confirmed'])->count();

        // TOTAL SALES (SEMUA WAKTU)
        // Semua uang yang statusnya Confirmed atau Completed
        $totalSales = Booking::whereIn('status', ['confirmed', 'completed'])->sum('total_price');

        // PERLU KONFIRMASI
        $pendingCount = Booking::where('status', 'pending')->count();


        // ==========================================
        // 2. DATA UNTUK TABEL & CAROUSEL
        // ==========================================
        
        $courts = Court::all();

        // 5 Booking Terakhir
        $recentBookings = Booking::with(['user', 'court'])
            ->latest()
            ->take(5)
            ->get();


        // ==========================================
        // 3. DATA UNTUK CHART
        // ==========================================

        // A. PIE CHART
        $pieData = Booking::join('courts', 'bookings.court_id', '=', 'courts.id')
            ->select('courts.type', \DB::raw('count(*) as total'))
            ->groupBy('courts.type')
            ->pluck('total', 'type')
            ->all();
        $pieLabels = array_keys($pieData);
        $pieValues = array_values($pieData);

        // B. LINE CHART (Pendapatan Bulanan)
        $chartIncome = [];
        $monthlyStats = Booking::select(
                \DB::raw('MONTH(created_at) as month'), 
                \DB::raw('SUM(total_price) as total')
            )
            ->whereIn('status', ['confirmed', 'completed']) // <--- Perubahan Disini
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        for ($i = 1; $i <= 12; $i++) {
            $chartIncome[] = $monthlyStats[$i] ?? 0;
        }

        return view('admin.dashboard', compact(
            'todaysIncome', 'newUsers', 'activeBookings', 'totalSales', 
            'pendingCount', 'recentBookings', 'courts', 
            'pieLabels', 'pieValues', 'chartIncome'
        ));
    }

    // --- FUNCTION BOOKING (JANGAN LUPA TAMBAHKAN INI JUGA) ---
    // Supaya menu "Data Booking" di sidebar berfungsi

    public function bookings(Request $request)
    {
        // Ambil input dari form
        $search = $request->input('search');
        $status = $request->input('status'); // Tambahan: Ambil status
        $date   = $request->input('date');   // Tambahan: Ambil tanggal

        $bookings = Booking::with(['user', 'court'])
            // 1. Logic SEARCH (Nama User atau Nama Lapangan)
            ->when($search, function ($query, $search) {
                // Kita bungkus dalam where(function) agar logic AND/OR tidak bertabrakan dengan filter status
                $query->where(function($q) use ($search) {
                    $q->whereHas('user', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('court', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    });
                });
            })
            // 2. Logic FILTER STATUS
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            // 3. Logic FILTER TANGGAL (Berdasarkan tanggal main)
            ->when($date, function ($query, $date) {
                return $query->whereDate('start_time', $date);
            })
            ->latest() // Urutkan dari yang terbaru
            ->paginate(10)
            ->withQueryString(); // <--- PENTING: Supaya saat klik "Next Page", filter search/status tidak hilang

        return view('admin.bookings.index', compact('bookings'));
    }

    public function approveBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'approved'; // Atau 'paid', sesuaikan database
        $booking->save();
        return redirect()->back()->with('success', 'Booking disetujui.');
    }

    public function rejectBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'rejected';
        $booking->save();
        return redirect()->back()->with('error', 'Booking ditolak.');
    }
}