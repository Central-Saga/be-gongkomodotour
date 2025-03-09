<?php

namespace Database\Factories;

use App\Models\EmailBlast;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmailBlast>
 */
class EmailBlastFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subject'        => $this->faker->sentence,
            'body'           => $this->faker->paragraph,
            'recipient_type' => $this->faker->randomElement(['all_customers', 'subscribers', 'spesific_list']),
            'status'         => $this->faker->randomElement(['draft', 'scheduled', 'sent', 'failed']),
            'scheduled_at'   => $this->faker->optional()->dateTime(),
            'sent_at'        => $this->faker->optional()->dateTime(),
            'created_at'     => now(),
            'updated_at'     => now(),
        ];
    }
}