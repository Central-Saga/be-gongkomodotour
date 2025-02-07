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
        Trips::factory()->count(10)->create();
    }
}
