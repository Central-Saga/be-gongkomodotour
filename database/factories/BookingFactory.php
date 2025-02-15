<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'trip_id' => $this->faker->randomElement(Trips::pluck('id')->toArray()),
            'customer_id' => $this->faker->randomElement(Customer::pluck('id')->toArray()),
            'boat_id' => $this->faker->randomElement(Boat::pluck('id')->toArray()),
            'cabin_id' => $this->faker->randomElement(Cabin::pluck('id')->toArray()),
            'user_id' => $this->faker->randomElement(User::pluck('id')->toArray()),
            'hotel_occupancy_id' => $this->faker->randomElement(HotelOccupancy::pluck('id')->toArray()),
            'total_price' => $this->faker->randomFloat(2, 0, 1000),
            'total_pax' => $this->faker->numberBetween(1, 10),
            'status' => $this->faker->randomElement(['Pending', 'Confirmed', 'Cancelled']),
        ];
    }
}
