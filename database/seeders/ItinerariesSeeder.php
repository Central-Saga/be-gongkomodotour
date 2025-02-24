<?php

namespace Database\Seeders;

use App\Models\Itineraries;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ItinerariesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Itineraries::factory()->count(10)->create();
    }
}
