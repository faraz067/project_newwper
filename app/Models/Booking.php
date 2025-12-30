<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    // Kita gunakan $fillable agar lebih aman dan jelas kolom mana yang boleh diisi
    protected $fillable = [
        'user_id',
        'court_id',
        'start_time',
        'end_time',
        'total_price',
        'status',
        'payment_proof',
        'extra_charge', // <--- KOLOM BARU (Denda/Tambahan)
        'note',         // <--- KOLOM BARU (Catatan Staff)
    ];

    // Agar tanggal otomatis dibaca sebagai Carbon (memudahkan format tanggal)
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Relasi ke User (Penyewa)
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Lapangan
    public function court() {
        return $this->belongsTo(Court::class);
    }
}