<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trips;

class TripsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trips = [
            [
                'name' => 'Trip 1',
                'include' => 'Trip 1 Include',
                'exclude' => 'Trip 1 Exclude',
                'note' => 'Trip 1 Note',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'meeting_point' => 'Meeting Point 1',
                'type' => 'Open Trip',
                'status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($trips as $trip) {
            Trips::create($trip);
        }

        Trips::factory()->count(10)->create();
    }
}
