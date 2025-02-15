<?php

namespace Database\Factories;

use App\Models\Trips;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FlightSchedule>
 */
class FlightScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'trip_id'   => Trips::factory(),
            'route'     => $this->faker->city . ' - ' . $this->faker->city,
            'eta_time'  => $this->faker->time('H:i:s'),
            'eta_text'  => $this->faker->sentence(3),
            'etd_time'  => $this->faker->time('H:i:s'),
            'etd_text'  => $this->faker->sentence(3),
        ];
    }
}
