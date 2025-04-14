<?php

namespace Database\Factories;

use App\Models\Customers;
use App\Models\User;
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
        // 70% kemungkinan nasionality Indonesia
        $isIndonesia = fake()->boolean(50);
        $nasionality = $isIndonesia ? 'Indonesia' : fake()->country();

        return [
            'user_id' => null,
            'alamat' => $this->faker->address(),
            'no_hp' => $this->faker->phoneNumber(),
            'nasionality' => $nasionality,
            'region' => $isIndonesia ? 'Domestic' : 'Overseas',
            'status' => 'Aktif',
        ];
    }
}
