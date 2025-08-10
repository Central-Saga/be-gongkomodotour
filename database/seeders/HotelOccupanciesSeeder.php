<?php

namespace Database\Seeders;

use App\Models\HotelOccupancies;
use App\Models\Surcharge;
use Illuminate\Database\Seeder;

class HotelOccupanciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HotelOccupancies::factory()->count(10)->create()->each(function ($hotelOccupancy) {
            Surcharge::factory()->count(2)->create([
                'hotel_occupancy_id' => $hotelOccupancy->id
            ]);
        });
    }
}
