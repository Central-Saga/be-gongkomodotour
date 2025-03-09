<?php

namespace Database\Factories;

use App\Models\EmailBlast;
use App\Models\EmailBlastRecipient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmailBlastRecipient>
 */
class EmailBlastRecipientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email_blast_id'  => EmailBlast::factory(),
            'recipient_email' => $this->faker->unique()->safeEmail,
            'status'          => $this->faker->randomElement(['pending', 'sent', 'failed']),
            'created_at'      => now(),
            'updated_at'      => now(),
        ];
    }
}