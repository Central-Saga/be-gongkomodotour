<?php

namespace Database\Factories;

use App\Models\Customers;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customers>
 */
class CustomersFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customers::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomDigitNotNull,
            'alamat' => $this->faker->address(),
            'no_hp' => $this->faker->phoneNumber(),
            'nasionality' => $this->faker->country(),
            'region' => $this->faker->state(),
            'status' => $this->faker->randomElement(['Aktif', 'Non Aktif']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}