<?php

namespace Database\Seeders;

use App\Models\TripDuration;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TripDurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tripDurations = [
            [
                'trip_id' => 1,
                'duration_label' => '2 Day 1 Night',
                'duration_days' => 2,
                'duration_nights' => 1,
                'status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trip_id' => 1,
                'duration_label' => '3 Day 2 Night',
                'duration_days' => 3,
                'duration_nights' => 2,
                'status' => 'Aktif',
            ],
            [
                'trip_id' => 1,
                'duration_label' => '4 Day 3 Night',
                'duration_days' => 4,
                'duration_nights' => 3,
                'status' => 'Aktif',
            ],
        ];

        foreach ($tripDurations as $tripDuration) {
            TripDuration::create($tripDuration);
        }

        // TripDuration::factory()->count(10)->create();
    }
}
