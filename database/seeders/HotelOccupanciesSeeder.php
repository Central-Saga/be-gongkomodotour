<?php

namespace Database\Seeders;

use App\Models\HotelOccupancies;
use Illuminate\Database\Seeder;

class HotelOccupanciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HotelOccupancies::factory()->count(50)->create();
    }
}