<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Trips;
use App\Models\Customers;
use App\Models\Boat;
use App\Models\Cabin;
use App\Models\User;
use App\Models\HotelOccupancies;
use App\Models\TripDuration;

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
            'trip_duration_id' => $this->faker->randomElement(TripDuration::pluck('id')->toArray()),
            'customer_id' => $this->faker->randomElement(Customers::pluck('id')->toArray()),
            'boat_id' => $this->faker->randomElement(Boat::pluck('id')->toArray()),
            'cabin_id' => $this->faker->randomElement(Cabin::pluck('id')->toArray()),
            'user_id' => $this->faker->randomElement(User::pluck('id')->toArray()),
            'hotel_occupancy_id' => $this->faker->randomElement(HotelOccupancies::pluck('id')->toArray()),
            'total_price' => 0,
            'total_pax' => $this->faker->numberBetween(1, 10),
            'status' => $this->faker->randomElement(['Pending', 'Confirmed', 'Cancelled']),
        ];
    }
}
