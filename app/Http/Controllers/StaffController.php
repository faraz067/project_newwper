<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking; 
use Carbon\Carbon;       

class StaffController extends Controller
{
    /**
     * Dashboard Staff dengan Statistik & Search
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        // 1. Booking Hari Ini (Untuk Card di Sisi Kiri)
        $todaysBookings = Booking::whereDate('start_time', Carbon::today())
                            ->with(['user', 'court']) 
                            ->orderBy('start_time', 'asc')
                            ->get();

        // 2. Semua Riwayat dengan Fitur Search (Untuk List di Sisi Kanan)
        $allBookings = Booking::with(['user', 'court'])
                        ->when($search, function($query) use ($search) {
                            $query->where(function($q) use ($search) {
                                $q->whereHas('user', function($userQuery) use ($search) {
                                    $userQuery->where('name', 'like', "%{$search}%");
                                })
                                ->orWhereHas('court', function($courtQuery) use ($search) {
                                    $courtQuery->where('name', 'like', "%{$search}%");
                                })
                                ->orWhere('id', 'like', "%{$search}%");
                            });
                        })
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);

        return view('staff.dashboard', compact('todaysBookings', 'allBookings'));
    }

    /**
     * Update Status: Terima (Confirmed), Tolak (Cancelled), Selesai (Completed)
     */
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        if ($request->has('status')) {
            $status = $request->status;

            // Logika Penolakan: Di sistem kamu 'rejected' diarahkan ke status 'cancelled'
            if ($status == 'rejected' || $status == 'reject') {
                $status = 'cancelled';
            }

            $booking->update(['status' => $status]);
        }

        return redirect()->back()->with('success', 'Status booking berhasil diupdate!');
    }

    /**
     * Input Biaya Tambahan (Denda/Sewa Alat)
     * Sudah dilengkapi pembersih input otomatis (Regex)
     */
    public function charge(Request $request, $id)
    {
        $request->validate([
            'extra_charge' => 'required', // Kita validasi string dulu karena akan dibersihkan
            'note' => 'nullable|string|max:255'
        ]);

        $booking = Booking::findOrFail($id);

        // MEMBERSIHKAN INPUT: 
        // Jika staff input "Rp 50.000", sistem akan ambil "50000" saja.
        $cleanAmount = preg_replace('/[^0-9]/', '', $request->extra_charge);

        if (empty($cleanAmount)) {
            $cleanAmount = 0;
        }

        // Simpan ke Database
        $booking->update([
            'extra_charge' => (int) $cleanAmount,
            'note' => $request->note
        ]);

        $formatted = number_format($cleanAmount, 0, ',', '.');
        return redirect()->back()->with('success', "Biaya sebesar Rp $formatted berhasil dicatat!");
    }
}