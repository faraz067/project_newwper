<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Court; // Pastikan Model sudah di-import

class HomeController extends Controller
{
    public function index()
    {
        // Ambil data lapangan
        $courts = Court::all(); 

        // Kirim ke view 'welcome'
        return view('welcome', compact('courts')); 
    }
}