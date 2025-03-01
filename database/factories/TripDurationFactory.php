<?php

namespace Database\Factories;

use App\Models\Trips;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TripDuration>
 */
class TripDurationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'trip_id'         => Trips::factory(),
            'duration_label'  => $this->faker->randomElement(['1 day', '2 days', '3 days', '4 days', '5 days']),
            'duration_days'   => $this->faker->numberBetween(1, 10),
            'duration_nights' => $this->faker->numberBetween(1, 10),
            'status'          => $this->faker->randomElement(['Aktif', 'Non Aktif']),
        ];
    }
}
