<?php

namespace Database\Factories;

use App\Models\Trips;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Surcharge>
 */
class SurchargeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'trip_id' => $this->faker->randomElement(Trips::pluck('id')->toArray()),
            'season' => $this->faker->randomElement(['High Season', 'Low Season']),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'surcharge_price' => $this->faker->randomFloat(2, 0, 100),
            'status' => $this->faker->randomElement(['Aktif', 'Non Aktif']),
        ];
    }
}
