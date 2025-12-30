<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    use HasFactory;

    // Tambahkan 'type' ke dalam sini
    protected $fillable = ['name', 'type', 'price_per_hour'];
}