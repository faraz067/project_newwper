<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    use HasFactory;

    /**
     * fillable diubah agar sesuai dengan migration terbaru.
     * Kita hapus 'type' dan 'price_per_hour', ganti dengan 'price'.
     */
    protected $fillable = [
        'name', 
        'price'
    ];

    // Relasi ke Booking (Opsional tapi disarankan agar riwayat lancar)
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}