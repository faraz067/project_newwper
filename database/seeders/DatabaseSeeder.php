<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Court;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun STAFF (Admin)
        User::create([
            'name' => 'Staff Admin',
            'email' => 'staff@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'staff',
        ]);

        // 2. Buat Akun USER (Penyewa)
        User::create([
            'name' => 'Andi Penyewa',
            'email' => 'andi@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // 3. Buat Data LAPANGAN
        Court::create([
            'name' => 'Futsal A (Rumput Sintetis)',
            'type' => 'Futsal',
            'price_per_hour' => 100000,
        ]);

        Court::create([
            'name' => 'Futsal B (Matras)',
            'type' => 'Futsal',
            'price_per_hour' => 80000,
        ]);

        Court::create([
            'name' => 'Badminton Hall 1',
            'type' => 'Badminton',
            'price_per_hour' => 50000,
        ]);
    }
}