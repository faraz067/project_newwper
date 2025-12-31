<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN
        $admin = User::firstOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        // STAFF
        $staff = User::firstOrCreate(
            ['email' => 'staff@gmail.com'],
            [
                'name' => 'Pak Petugas',
                'password' => Hash::make('password123'),
            ]
        );

        if (!$staff->hasRole('staff')) {
            $staff->assignRole('staff');
        }

        // USER / PENYEWA
        $user = User::firstOrCreate(
            ['email' => 'andi@gmail.com'],
            [
                'name' => 'Andi Penyewa',
                'password' => Hash::make('password123'),
            ]
        );

        if (!$user->hasRole('user')) {
            $user->assignRole('user');
        }
    }
}
