<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Court;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bersihkan database agar tidak duplikat
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Court::truncate();
        Schema::enableForeignKeyConstraints();

        // 2. Buat Akun STAFF (Wajib ID 1 untuk link testing)
        User::create([
            'id' => 1,
            'name' => 'Staff Admin',
            'email' => 'staff@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'staff',
        ]);

        // 3. Buat Akun USER (Wajib ID 2 untuk link testing)
        User::create([
            'id' => 2,
            'name' => 'Andi Penyewa',
            'email' => 'andi@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // 4. Buat Data LAPANGAN (Gunakan kolom 'price', hapus kolom 'type')
        Court::create([
            'name' => 'Lapangan Basket Indoor',
            'price' => 70000,
        ]);

        Court::create([
            'name' => 'Meja Pingpong 1',
            'price' => 50000,
        ]);

        Court::create([
            'name' => 'Lapangan Padel Premium',
            'price' => 250000,
        ]);

        Court::create([
            'name' => 'Badminton Hall A',
            'price' => 30000,
        ]);
    }
}
