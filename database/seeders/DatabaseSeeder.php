<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Court;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. BUAT ROLE
        $roleStaff = Role::firstOrCreate(['name' => 'staff']);
        $roleUser = Role::firstOrCreate(['name' => 'user']);

        // 2. BUAT USER (STAFF)
        $staff = User::create([
            'name' => 'Staff Admin',
            'email' => 'staff@gmail.com',
            'password' => bcrypt('password123'),
        ]);
        $staff->assignRole($roleStaff);

        // 3. BUAT USER (PENYEWA)
        $user = User::create([
            'name' => 'Andi Penyewa',
            'email' => 'andi@gmail.com',
            'password' => bcrypt('password123'),
        ]);
        $user->assignRole($roleUser);

        // 4. BUAT DATA LAPANGAN
        // Perhatikan: kuncinya sekarang 'price_per_hour' (bukan 'price')
        
        Court::create([
            'name' => 'Lapangan Basket Indoor', 
            'type' => 'Indoor',
            'price_per_hour' => 70000, 
        ]);

        Court::create([
            'name' => 'Meja Pingpong 1', 
            'type' => 'Indoor',
            'price_per_hour' => 50000,
        ]);

        Court::create([
            'name' => 'Lapangan Padel Premium', 
            'type' => 'Outdoor',
            'price_per_hour' => 250000,
        ]);

        Court::create([
            'name' => 'Badminton Hall A', 
            'type' => 'Indoor',
            'price_per_hour' => 30000,
        ]);
    }
}