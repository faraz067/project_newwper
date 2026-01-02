<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// 👇 PENTING: Jangan panggil App\Models\Field karena file itu TIDAK ADA.
// 👇 Panggil yang benar-benar ada saja.
use App\Models\User;
use App\Models\Court; 

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'court_id',     
        'start_time',
        'end_time',
        'total_price',
        'status',
        'payment_proof',
        'extra_charge', 
        'note',        
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Relasi ke User
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Court (Sesuai nama asli model kamu)
    public function court() {
        return $this->belongsTo(Court::class, 'court_id');
    }

    // ==========================================
    // PERBAIKAN UTAMA ADA DI SINI
    // ==========================================
    public function field()
    {
        // Trik: Karena file 'Field.php' tidak ada, kita sambungkan ke 'Court::class'.
        // Jadi saat dashboard memanggil $booking->field, dia sebenarnya mengambil data Court.
        return $this->belongsTo(Court::class, 'court_id'); 
    }
}