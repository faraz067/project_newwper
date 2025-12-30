<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Staff
        User::create([
            'name' => 'Pak Petugas',
            'email' => 'staff@gmail.com',
            'password' => Hash::make('password123'), // Passwordnya 'password123'
            'role' => 'staff', // PENTING: Role-nya staff
        ]);

        // 2. Buat Akun User Biasa (Contoh Penyewa)
        User::create([
            'name' => 'Andi Penyewa',
            'email' => 'andi@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);
    }
}
