<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    use HasFactory;

    // Beri tahu Laravel kalau tabelnya bernama 'courts'
    protected $table = 'courts';

    protected $fillable = [
        'name',
        'type',             // Kolom baru
        'price_per_hour',   // Sesuai gambar
        'status',           // Sesuai gambar (ENUM)
        'photo',            // Sesuai gambar
    ];

    // --- TAMBAHAN DARI TEMAN (PENTING) ---
    
    // Ini atribut tambahan biar frontend gampang ambil harga
    protected $appends = ['price'];

    public function getPriceAttribute()
    {
        return $this->price_per_hour;
    }

    // Relasi ke Booking (PENTING: Tanpa ini dashboard admin error saat load booking)
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}