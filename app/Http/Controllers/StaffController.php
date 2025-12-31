<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking; 
use Carbon\Carbon;      

class StaffController extends Controller
{
    // Halaman Dashboard Staff
    public function index()
    {
        // 1. Ambil data booking KHUSUS HARI INI
        $todaysBookings = Booking::whereDate('start_time', Carbon::today())
                            ->with(['user', 'court']) 
                            ->orderBy('start_time', 'asc')
                            ->get();

        // 2. Ambil SEMUA riwayat booking (untuk tabel bawah)
        $allBookings = Booking::with(['user', 'court'])
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('staff.dashboard', compact('todaysBookings', 'allBookings'));
    }

    // Proses Update Status (Selesai/Terima/Tolak)
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        if ($request->has('status')) {
            $booking->update(['status' => $request->status]);
        }

        return redirect()->back()->with('success', 'Status booking berhasil diperbarui!');
    }

    // ============================================================
    // FIX: Fungsi Input Denda (Menangani Masalah Titik/Rp 100)
    // ============================================================
    public function addCharge(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        // Ambil data input denda
        $inputCharge = $request->extra_charge;

        // PROTEKSI: Hapus semua karakter selain angka (titik, koma, Rp)
        // Jika input "100.000", hasilnya jadi "100000"
        $cleanAmount = preg_replace('/[^0-9]/', '', $inputCharge);

        // Validasi setelah dibersihkan
        if (!is_numeric($cleanAmount) && !empty($inputCharge)) {
            return redirect()->back()->withErrors(['extra_charge' => 'Format denda harus berupa angka.']);
        }

        $booking->update([
            'extra_charge' => (int) $cleanAmount,
            'note' => $request->note
        ]);

        return redirect()->back()->with('success', 'Biaya tambahan Rp ' . number_format($cleanAmount, 0, ',', '.') . ' berhasil disimpan!');
    }
}