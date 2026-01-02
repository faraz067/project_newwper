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
        // 1. PENDAPATAN HARI INI
        $todaysIncome = 0; 
        if(class_exists(Booking::class)) {
            $todaysIncome = Booking::whereDate('created_at', Carbon::today())
                ->where('status', 'paid') // Sesuaikan status: 'paid' atau 'approved'
                ->sum('total_price');
        }

        // 2. USER BARU
        $newUsers = User::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // 3. BOOKING AKTIF
        $activeBookings = 0;
        if(class_exists(Booking::class)) {
            // Menghitung booking yang belum selesai
            $activeBookings = Booking::whereIn('status', ['pending', 'confirmed', 'paid', 'approved'])->count();
        }

        // 4. TOTAL SALES
        $totalSales = 0;
        if(class_exists(Booking::class)) {
            $totalSales = Booking::where('status', 'paid')->sum('total_price');
        }

        // 5. DATA LAPANGAN (Untuk Carousel)
        $courts = Court::all();

        // --- BAGIAN BARU: DATA UNTUK CHART ---

        // A. DATA PIE CHART (Lapangan Terpopuler)
        $pieLabels = [];
        $pieValues = [];
        
        if(class_exists(Booking::class)) {
            // Mengambil jumlah booking berdasarkan tipe lapangan
            $pieData = Booking::join('courts', 'bookings.court_id', '=', 'courts.id')
                ->select('courts.type', DB::raw('count(*) as total'))
                ->groupBy('courts.type')
                ->pluck('total', 'type')
                ->all();

            $pieLabels = array_keys($pieData);   // Hasil: ['Futsal', 'Badminton']
            $pieValues = array_values($pieData); // Hasil: [12, 5]
        }

        // B. DATA LINE CHART (Pendapatan Bulanan Tahun Ini)
        $chartIncome = [];
        
        if(class_exists(Booking::class)) {
            $monthlyStats = Booking::select(
                DB::raw('MONTH(created_at) as month'), 
                DB::raw('SUM(total_price) as total')
            )
            ->where('status', 'paid') // Pastikan hanya menghitung yang sudah bayar
            ->whereYear('created_at', date('Y')) // Hanya tahun ini
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

            // Loop 1 sampai 12 (Januari - Desember)
            for ($i = 1; $i <= 12; $i++) {
                // Jika bulan $i ada datanya, pakai datanya. Jika tidak, isi 0.
                $chartIncome[] = $monthlyStats[$i] ?? 0;
            }
        } else {
            // Default jika belum ada booking (semua bulan 0)
            $chartIncome = array_fill(0, 12, 0); 
        }

        return view('admin.dashboard', compact(
            'todaysIncome', 
            'newUsers', 
            'activeBookings', 
            'totalSales', 
            'courts',
            // Kirim data chart ke view
            'pieLabels',
            'pieValues',
            'chartIncome'
        ));
    }

    // --- FUNCTION BOOKING (JANGAN LUPA TAMBAHKAN INI JUGA) ---
    // Supaya menu "Data Booking" di sidebar berfungsi

    public function bookings(Request $request)
    {
        $search = $request->input('search');

        $bookings = Booking::with(['user', 'court'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('court', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10); // Pagination 10 per halaman

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