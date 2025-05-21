<?php

namespace Database\Seeders;

use App\Models\Surcharge;
use App\Models\HotelOccupancies;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SurchargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hotelOccupancyIds = HotelOccupancies::pluck('id')->toArray();
        Surcharge::factory()->count(10)->create([
            'hotel_occupancy_id' => function () use ($hotelOccupancyIds) {
                return $hotelOccupancyIds[array_rand($hotelOccupancyIds)];
            }
        ]);
    }
}
