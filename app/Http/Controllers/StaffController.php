<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking; // <--- Kita butuh model Booking
use Carbon\Carbon;      // <--- Kita butuh ini untuk urus tanggal

class StaffController extends Controller
{
    // Halaman Dashboard Staff
    public function index()
    {
        // 1. Ambil data booking KHUSUS HARI INI
        $todaysBookings = Booking::whereDate('start_time', Carbon::today())
                            ->with(['user', 'court']) // Ambil data user & lapangan biar gak error
                            ->orderBy('start_time', 'asc')
                            ->get();

        // 2. Ambil SEMUA riwayat booking (untuk tabel bawah)
        $allBookings = Booking::with(['user', 'court'])
                        ->orderBy('created_at', 'desc')
                        ->get();

        // Kirim dua variabel ini ke View
        return view('staff.dashboard', compact('todaysBookings', 'allBookings'));
    }

    // Proses Update Status (Saat tombol Selesai/Terima ditekan)
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        // Update status sesuai request dari tombol
        if ($request->has('status')) {
            $booking->update(['status' => $request->status]);
        }

        return redirect()->back()->with('success', 'Status booking berhasil diperbarui!');
    }

    // Fungsi untuk Input Denda / Biaya Tambahan
    public function addCharge(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'extra_charge' => 'required|numeric|min:0',
            'note' => 'nullable|string'
        ]);

        $booking->update([
            'extra_charge' => $request->extra_charge,
            'note' => $request->note
        ]);

        return redirect()->back()->with('success', 'Biaya tambahan berhasil disimpan!');
    }
}