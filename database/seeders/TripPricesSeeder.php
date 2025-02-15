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
        TripPrices::factory()->count(10)->create();
    }
}
