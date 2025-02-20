<?php

namespace Database\Seeders;

use App\Models\TripPrices;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TripPricesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tripPrices = [
            [
                'trip_duration_id' => 1,
                'pax_min' => 1,
                'pax_max' => 1,
                'price_per_pax' => 1000000,
                'status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trip_duration_id' => 1,
                'pax_min' => 2,
                'pax_max' => 3,
                'price_per_pax' => 2000000,
                'status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trip_duration_id' => 1,
                'pax_min' => 4,
                'pax_max' => 5,
                'price_per_pax' => 3000000,
                'status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trip_duration_id' => 1,
                'pax_min' => 6,
                'pax_max' => 7,
                'price_per_pax' => 4000000,
                'status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trip_duration_id' => 1,
                'pax_min' => 8,
                'pax_max' => 9,
                'price_per_pax' => 5000000,
                'status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trip_duration_id' => 1,
                'pax_min' => 10,
                'pax_max' => 11,
                'price_per_pax' => 6000000,
                'status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($tripPrices as $tripPrice) {
            TripPrices::create($tripPrice);
        }

        TripPrices::factory()->count(10)->create();
    }
}
