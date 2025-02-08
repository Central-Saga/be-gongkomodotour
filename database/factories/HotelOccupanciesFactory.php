<?php

namespace Database\Factories;

use App\Models\HotelOccupancies;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HotelOccupancies>
 */
class HotelOccupanciesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HotelOccupancies::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hotel_name' => $this->faker->company(),
            'hotel_type' => $this->faker->randomElement(['Luxury', 'Budget', 'Boutique']),
            'occupancy' => $this->faker->numberBetween(1, 100),
            'price' => $this->faker->randomFloat(2, 50, 500),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}