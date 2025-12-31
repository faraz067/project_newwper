<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourtSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('courts')->insert([
            [
                'name' => 'Lapangan Futsal A',
                'type' => 'Futsal',
                'price_per_hour' => 100000,
                'status' => 'tersedia',
                'photo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lapangan Badminton B',
                'type' => 'Badminton',
                'price_per_hour' => 80000,
                'status' => 'tersedia',
                'photo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
