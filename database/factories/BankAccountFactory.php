<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BankAccount>
 */
class BankAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bank_name' => $this->faker->randomElement(array: ['BRI', 'BCA', 'Mandiri']),
            'account_name' => $this->faker->name,
            'account_number' => $this->faker->bankAccountNumber,
            'swift_code' => $this->faker->randomElement(array: ['BRI', 'BCA', 'Mandiri']),
            'status' => $this->faker->randomElement(array: ['Aktif', 'Non Aktif']),
        ];
    }
}
