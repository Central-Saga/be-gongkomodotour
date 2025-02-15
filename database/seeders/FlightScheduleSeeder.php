<?php

namespace Database\Seeders;

use App\Models\FlightSchedule;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FlightScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FlightSchedule::factory()->count(10)->create();
    }
}
