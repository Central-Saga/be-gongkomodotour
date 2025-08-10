<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faq>
 */
class FaqFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question'      => $this->faker->sentence,
            'answer'        => $this->faker->paragraphs(3, true),
            'category'      => $this->faker->randomElement(['Umum', 'Pembayaran', 'Pemesanan', 'Pembatalan', 'Lainnya']),
            'display_order' => $this->faker->numberBetween(1, 6),
            'status'        => $this->faker->randomElement(['Aktif', 'Non Aktif']),
        ];
    }
}
