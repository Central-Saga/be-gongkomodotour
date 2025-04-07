<?php

namespace Database\Seeders;

use App\Models\Trips;
use App\Models\Surcharge;
use App\Models\TripPrices;
use App\Models\Itineraries;
use App\Models\TripDuration;
use App\Models\AdditionalFee;
use App\Models\FlightSchedule;
use Illuminate\Database\Seeder;

class TripsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate 4 highlighted trips with related data
        Trips::factory()
            ->count(4)
            ->highlighted()
            ->has(FlightSchedule::factory()->count(1))
            ->has(Itineraries::factory()->count(3))
            ->has(
                TripDuration::factory()
                    ->count(1)
                    ->has(TripPrices::factory()->count(6))
            )
            ->has(AdditionalFee::factory()->count(2)) // 2 additional fees per trip
            ->has(Surcharge::factory()->count(1)) // 1 surcharge per trip
            ->create();

        // Generate 8 non-highlighted trips with related data
        Trips::factory()
            ->count(8)
            ->has(FlightSchedule::factory()->count(1))
            ->has(Itineraries::factory()->count(3))
            ->has(
                TripDuration::factory()
                    ->count(1)
                    ->has(TripPrices::factory()->count(6))
            )
            ->has(AdditionalFee::factory()->count(2)) // 2 additional fees per trip
            ->has(Surcharge::factory()->count(1)) // 1 surcharge per trip
            ->create();
    }
}
