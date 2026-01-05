<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Court;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. BUAT ROLE TERLEBIH DAHULU (PENTING!)
        // UserSeeder butuh role ini sudah ada, makanya kita buat di paling atas.
        Role::firstOrCreate(['name' => 'admin']); // Tambahan: UserSeeder butuh role admin
        Role::firstOrCreate(['name' => 'staff']);
        Role::firstOrCreate(['name' => 'user']);

        // 2. PANGGIL USER SEEDER
        // Ini akan menjalankan file UserSeeder.php yang logic-nya lebih rapi
        $this->call([
            UserSeeder::class,
        ]);

        // 3. BUAT DATA LAPANGAN
        // Saya ubah jadi 'firstOrCreate' supaya kalau di-seed 2x tidak jadi dobel
        
        Court::firstOrCreate(
            ['name' => 'Lapangan Basket Indoor'], // Cek berdasarkan nama
            [
                'type' => 'Indoor',
                'price_per_hour' => 70000,
            ]
        );

        Court::firstOrCreate(
            ['name' => 'Meja Pingpong 1'], 
            [
                'type' => 'Indoor',
                'price_per_hour' => 50000,
            ]
        );

        Court::firstOrCreate(
            ['name' => 'Lapangan Padel Premium'],
            [
                'type' => 'Outdoor',
                'price_per_hour' => 250000,
            ]
        );

        Court::firstOrCreate(
            ['name' => 'Badminton Hall A'],
            [
                'type' => 'Indoor',
                'price_per_hour' => 30000,
            ]
        );
    }
}