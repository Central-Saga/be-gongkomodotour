<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\AdditionalFee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookingFee>
 */
class BookingFeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'booking_id' => $this->faker->randomElement(Booking::pluck('id')->toArray()),
            'additional_fee_id' => $this->faker->randomElement(AdditionalFee::pluck('id')->toArray()),
            'fee_type' => $this->faker->randomElement(['Open Trip', 'Private Trip']),
            'total_price' => $this->faker->randomFloat(2, 100, 1000),
        ];
    }
}
