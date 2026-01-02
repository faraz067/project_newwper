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

        // Ambil booking yang SUDAH ADA untuk ditampilkan di sidebar
        // UBAH DISINI: Filter status 'cancelled' agar slot yang batal tidak muncul
        $existingBookings = Booking::whereDate('start_time', $date)
            ->where('status', '!=', 'cancelled') // <-- PENTING: Jangan tampilkan yang sudah cancel
            ->where('status', '!=', 'rejected')  // (Opsional) Jaga-jaga kalau ada status rejected
            ->with(['court', 'user']) 
            ->orderBy('start_time')
            ->get();

        return view('user.booking', compact('courts', 'existingBookings', 'date'));
    }

    // ============================================================
    // 2. PROSES SIMPAN BOOKING
    // ============================================================
    public function store(Request $request)
    {
        // 1. VALIDASI INPUT
        $request->validate([
            'court_id' => 'required|exists:courts,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        // 2. PARSE TANGGAL & JAM
        $start = Carbon::parse($request->date . ' ' . $request->start_time);
        $end = Carbon::parse($request->date . ' ' . $request->end_time);

        // 3. HITUNG DURASI
        $durationInHours = $start->floatDiffInHours($end);

        // 4. HITUNG HARGA
        $court = Court::findOrFail($request->court_id);
        
        // Prioritas kolom price_per_hour, fallback ke price
        $pricePerHour = $court->price_per_hour ?? $court->price ?? 0;
        
        $totalPrice = $pricePerHour * $durationInHours;

        // 5. CEK BENTROK JADWAL
        // Logic: Cek apakah ada booking lain di jam yang sama, KECUALI yang statusnya cancelled/rejected
        $bentrok = Booking::where('court_id', $request->court_id)
            ->whereDate('start_time', $request->date)
            ->where('status', '!=', 'cancelled') // <-- PENTING: Slot yang dicancel BOLEH ditempati lagi
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

        // 6. SIMPAN KE DATABASE
        Booking::create([
            'user_id' => Auth::id(),
            'court_id' => $request->court_id,
            'start_time' => $start,
            'end_time' => $end,
            'total_price' => abs($totalPrice), 
            'status' => 'pending', // Default status awal selalu pending
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
            
            // Update path bukti bayar
            // Status TIDAK diubah otomatis jadi confirmed, biarkan admin/staff yang cek
            $booking->update([
                'payment_proof' => $path
            ]);
        }

        return redirect()->back()->with('success', 'Bukti berhasil diupload! Tunggu konfirmasi admin.');
    }
}