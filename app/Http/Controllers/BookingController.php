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
    // 1. HALAMAN FORM BOOKING
    // ============================================================
    public function create(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        $courts = Court::all();

        // Ambil booking yang SUDAH ADA (tampilan list kanan)
        $existingBookings = Booking::whereDate('start_time', $date)
            ->where('status', '!=', 'rejected') 
            ->with(['court', 'user']) // Tambahkan user agar nama penyewa muncul
            ->orderBy('start_time')
            ->get();

        return view('user.booking', compact('courts', 'existingBookings', 'date'));
    }

    // ============================================================
    // 2. PROSES SIMPAN BOOKING (Fix Harga Rp 0)
    // ============================================================
    public function store(Request $request)
    {
        $request->validate([
            'court_id' => 'required|exists:courts,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $start = Carbon::parse($request->date . ' ' . $request->start_time);
        $end = Carbon::parse($request->date . ' ' . $request->end_time);

        // A. HITUNG DURASI
        $durationInMinutes = $end->diffInMinutes($start);
        $durationInHours = $durationInMinutes / 60;

        // B. AMBIL DATA LAPANGAN & HITUNG HARGA
        $court = Court::findOrFail($request->court_id);
        
        // FIX: Menggunakan $court->price (BUKAN price_per_hour)
        $totalPrice = $court->price * $durationInHours;

        // C. CEK BENTROK JADWAL
        $bentrok = Booking::where('court_id', $request->court_id)
            ->whereDate('start_time', $request->date)
            ->where('status', '!=', 'rejected')
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->where('start_time', '<', $end)
                      ->where('end_time', '>', $start);
                });
            })
            ->exists();

        if ($bentrok) {
            return back()
                ->withErrors(['msg' => 'Maaf, jam tersebut sudah terisi! Silakan pilih jam lain.'])
                ->withInput();
        }

        // D. SIMPAN KE DATABASE
        Booking::create([
            'user_id' => Auth::id(),
            'court_id' => $request->court_id,
            'start_time' => $start,
            'end_time' => $end,
            'total_price' => $totalPrice, // Sekarang nilainya benar (tidak 0)
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
        $booking = Booking::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            $booking->update(['payment_proof' => $path]);
        }

        return redirect()->back()->with('success', 'Bukti berhasil diupload!');
    }
}