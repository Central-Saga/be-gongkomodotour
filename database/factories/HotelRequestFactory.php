<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HotelRequest>
 */
class HotelRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transaction_id' => Transaction::factory(),
            'user_id' => User::factory(),
            'confirmed_note' => $this->faker->sentence,
            'requested_hotel_name' => $this->faker->randomElement(array: ['Ayana Komodo Resort', 'Meruorah Hotel']),
            'request_status' => $this->faker->randomElement(array: ['Menunggu Konfirmasi', 'Diterima', 'Ditolak']),
            'confirmed_price' => $this->faker->randomFloat(2, 100, 1000),
        ];
    }
}
