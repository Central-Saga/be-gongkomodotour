<?php

namespace Database\Factories;

use App\Models\TripDuration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TripPrices>
 */
class TripPricesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paxMin = $this->faker->numberBetween(1, 10);
        $paxMax = $this->faker->numberBetween($paxMin, $paxMin + 10);

        return [
            'trip_duration_id' => TripDuration::factory(),
            'pax_min'          => $paxMin,
            'pax_max'          => $paxMax,
            'price_per_pax'    => $this->faker->randomFloat(2, 50, 500),
            'status'           => $this->faker->randomElement(['Aktif', 'Non Aktif']),
        ];
    }
}
