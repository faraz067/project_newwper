<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Court;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    // ============================================================
    // 1. HALAMAN FORM BOOKING (Dengan Fitur Cek Jadwal Visual)
    // ============================================================
    public function create(Request $request)
    {
        // Ambil tanggal dari pilihan user (kalau kosong, pakai hari ini)
        $date = $request->input('date', date('Y-m-d'));

        // Ambil semua data lapangan
        $courts = Court::all();

        // Ambil booking yang SUDAH ADA di tanggal tersebut (untuk ditampilkan di list kanan)
        $existingBookings = Booking::whereDate('start_time', $date)
            ->where('status', '!=', 'rejected') // Hiraukan yang ditolak
            ->with('court') // Ambil data lapangan juga
            ->orderBy('start_time')
            ->get();

        return view('user.booking', compact('courts', 'existingBookings', 'date'));
    }

    // ============================================================
    // 2. PROSES SIMPAN BOOKING (Fix: Baris Duration Dihapus)
    // ============================================================
    public function store(Request $request)
    {
        // A. Validasi Input
        $request->validate([
            'court_id' => 'required|exists:courts,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time', // Jam selesai wajib setelah jam mulai
        ]);

        // B. Gabungkan Tanggal & Jam untuk perhitungan
        $start = Carbon::parse($request->date . ' ' . $request->start_time);
        $end = Carbon::parse($request->date . ' ' . $request->end_time);

        // C. Hitung Durasi & Harga
        $durationMinutes = $end->diffInMinutes($start);
        $durationHours = $durationMinutes / 60; // Konversi ke jam

        $court = Court::find($request->court_id);
        $totalPrice = $court->price_per_hour * $durationHours;

        // D. Cek Bentrok Jadwal (Anti Double Booking)
        $bentrok = Booking::where('court_id', $request->court_id)
            ->whereDate('start_time', $request->date)
            ->where('status', '!=', 'rejected')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_time', [$start, $end])
                      ->orWhereBetween('end_time', [$start, $end])
                      ->orWhere(function ($q) use ($start, $end) {
                          $q->where('start_time', '<', $start)
                            ->where('end_time', '>', $end);
                      });
            })
            ->exists();

        if ($bentrok) {
            return back()
                ->withErrors(['msg' => 'Maaf, jam tersebut sudah terisi! Silakan lihat daftar jadwal di sebelah kanan.'])
                ->withInput();
        }

        // E. Simpan ke Database
        Booking::create([
            'user_id' => Auth::id(),
            'court_id' => $request->court_id,
            'start_time' => $start,
            'end_time' => $end,
            // 'duration' => $durationMinutes,  <-- SAYA MATIKAN BARIS INI AGAR TIDAK ERROR
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        return redirect()->route('booking.history')->with('success', 'Booking berhasil! Silakan upload bukti bayar.');
    }

    // ============================================================
    // 3. RIWAYAT BOOKING USER
    // ============================================================
    public function history()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with('court')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.history', compact('bookings'));
    }

    // ============================================================
    // 4. UPLOAD BUKTI PEMBAYARAN
    // ============================================================
    public function uploadProof(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        // Validasi Gambar
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Proses Upload
        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            
            // Update Database
            $booking->update([
                'payment_proof' => $path
            ]);
        }

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload! Tunggu konfirmasi Admin.');
    }
}