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
}