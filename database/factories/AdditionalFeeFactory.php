<?php

namespace Database\Factories;

use App\Models\Trips;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdditionalFee>
 */
class AdditionalFeeFactory extends Factory
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
            'fee_category' => $this->faker->randomElement(['Parkir', 'Transfer', 'Tiket Masuk']),
            'price' => $this->faker->randomFloat(2, 0, 100),
            'region' => $this->faker->randomElement(['Domestic', 'Overseas']),
            'unit' => $this->faker->randomElement(['per_pax', 'per_5pax', 'per_day', 'per_guide']),
            'pax_min' => $this->faker->numberBetween(1, 10),
            'pax_max' => $this->faker->numberBetween(1, 10),
            'day_type' => $this->faker->randomElement(['Weekday', 'Weekend']),
            'status' => $this->faker->randomElement(['Aktif', 'Non Aktif']),
        ];
    }
}
