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
    'type',
    'price_per_hour', // <--- Harus sama dengan database
    'status',
    'photo',
];

    // Relasi ke Booking (Opsional tapi disarankan agar riwayat lancar)
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    protected $appends = ['price'];

    public function getPriceAttribute()
    {
        return $this->price_per_hour;
    }
}
