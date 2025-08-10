<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TripPrices;

class TripPricesFactory extends Factory
{
    protected $model = TripPrices::class;

    public function definition(): array
    {
        $priceRanges = [
            'Domestic' => [
                [1, 1, 1000000, 2000000],
                [2, 3, 500000, 1000000],
                [4, 5, 400000, 800000],
                [6, 7, 300000, 600000],
                [8, 9, 250000, 500000],
                [10, 999, 200000, 400000],
            ],
            'Overseas' => [
                [1, 1, 10000000, 20000000],
                [2, 3, 5000000, 10000000],
                [4, 5, 4000000, 8000000],
                [6, 7, 3000000, 6000000],
                [8, 9, 2500000, 5000000],
                [10, 999, 2000000, 4000000],
            ],
            'Domestic & Overseas' => [
                [1, 1, 5000000, 10000000],
                [2, 3, 2500000, 5000000],
                [4, 5, 2000000, 4000000],
                [6, 7, 1500000, 3000000],
                [8, 9, 1250000, 2500000],
                [10, 999, 1000000, 2000000],
            ],
        ];

        $region = $this->faker->randomElement(['Domestic', 'Overseas', 'Domestic & Overseas']);
        $range = $this->faker->randomElement($priceRanges[$region]);

        return [
            'trip_duration_id' => null, // Will be set by relationship
            'pax_min' => $range[0],
            'pax_max' => $range[1],
            'price_per_pax' => $this->faker->numberBetween($range[2], $range[3]),
            'status' => 'Aktif',
            'region' => $region,
        ];
    }
}
