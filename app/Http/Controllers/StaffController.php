<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking; 
use Carbon\Carbon;       

class StaffController extends Controller
{
    /**
     * Halaman Dashboard Staff dengan Fitur Search
     */
    public function index(Request $request)
    {
        // Ambil input search dari URL (jika ada)
        $search = $request->get('search');

        // 1. Ambil data booking KHUSUS HARI INI (Tanpa filter search agar staff tetap fokus jadwal hari ini)
        $todaysBookings = Booking::whereDate('start_time', Carbon::today())
                            ->with(['user', 'court']) 
                            ->orderBy('start_time', 'asc')
                            ->get();

        // 2. Ambil SEMUA riwayat booking dengan LOGIKA SEARCH
        $allBookings = Booking::with(['user', 'court'])
                        ->when($search, function($query) use ($search) {
                            $query->where(function($q) use ($search) {
                                // Cari berdasarkan Nama User
                                $q->whereHas('user', function($userQuery) use ($search) {
                                    $userQuery->where('name', 'like', "%{$search}%");
                                })
                                // ATAU Cari berdasarkan Nama Lapangan
                                ->orWhereHas('court', function($courtQuery) use ($search) {
                                    $courtQuery->where('name', 'like', "%{$search}%");
                                })
                                // ATAU Cari berdasarkan ID Booking
                                ->orWhere('id', 'like', "%{$search}%");
                            });
                        })
                        ->orderBy('created_at', 'desc')
                        ->paginate(15); // Menggunakan pagination agar loading lebih ringan

        return view('staff.dashboard', compact('todaysBookings', 'allBookings'));
    }

    /**
     * Update Status Booking (Confirm/Complete/Reject)
     */
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        if ($request->has('status')) {
            $booking->update(['status' => $request->status]);
        }

        return redirect()->back()->with('success', 'Status booking berhasil diperbarui!');
    }

    /**
     * Input Biaya Tambahan / Denda dengan Proteksi Angka
     * PERBAIKAN: Nama fungsi diubah jadi 'charge' agar sesuai dengan route web.php
     */
    public function charge(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        // Ambil data input
        $inputCharge = $request->extra_charge;

        // PROTEKSI: Hapus titik/koma/karakter lain. Contoh "150.000" -> "150000"
        $cleanAmount = preg_replace('/[^0-9]/', '', $inputCharge);

        // Validasi sederhana: pastikan hasil bersihnya adalah angka
        if (!is_numeric($cleanAmount) && !empty($inputCharge)) {
            return redirect()->back()->withErrors(['extra_charge' => 'Input harus berupa angka.']);
        }

        // Update database
        $booking->update([
            'extra_charge' => (int) $cleanAmount,
            'note' => $request->note // Pastikan kolom 'note' ada di database kamu
        ]);

        $formatted = number_format($cleanAmount, 0, ',', '.');
        return redirect()->back()->with('success', "Biaya tambahan Rp $formatted berhasil disimpan!");
    }
}