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
        TripDuration::factory()->count(10)->create();
    }
}
