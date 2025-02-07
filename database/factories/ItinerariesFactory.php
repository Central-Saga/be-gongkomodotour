<?php

namespace Database\Factories;

use App\Models\Trips;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Itineraries>
 */
class ItinerariesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'trip_id'     => Trips::factory(), // generate related trip if not provided
            'day_number'  => $this->faker->numberBetween(1, 10),
            'activities'  => $this->faker->paragraph(),
        ];
    }
}
