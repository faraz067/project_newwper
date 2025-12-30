<?php

namespace App\Models;

// 👇 JANGAN LUPA BARIS INI
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    // Agar tanggal otomatis dibaca sebagai Carbon (memudahkan format tanggal)
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function court() {
        return $this->belongsTo(Court::class);
    }
}