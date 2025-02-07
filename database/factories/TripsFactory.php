<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trips>
 */
class TripsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'include' => $this->faker->paragraph(),
            'exclude' => $this->faker->paragraph(),
            'note' => $this->faker->sentence(),
            'duration' => $this->faker->randomElement(['1 day', '2 days', '3 days']),
            'start_time' => $this->faker->time('H:i:s'),
            'end_time' => $this->faker->time('H:i:s'),
            'meeting_point' => $this->faker->address(),
            'type' => $this->faker->randomElement(['Open Trip', 'Private Trip']),
            'status' => $this->faker->randomElement(['Aktif', 'Non Aktif']),
        ];
    }
}
