<?php

namespace Database\Factories;

use App\Models\Boat;
use App\Models\User;
use App\Models\Cabin;
use App\Models\Trips;
use App\Models\Customers;
use App\Models\TripDuration;
use App\Models\AdditionalFee;
use App\Models\HotelOccupancies;
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
            'trip_duration_id' => $this->faker->randomElement(TripDuration::pluck('id')->toArray()),
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->unique()->safeEmail(),
            'customer_address' => $this->faker->address(),
            'customer_country' => $this->faker->country(),
            'customer_phone' => $this->faker->phoneNumber(),
            'user_id' => $this->faker->randomElement(User::pluck('id')->toArray()),
            'hotel_occupancy_id' => $this->faker->randomElement(HotelOccupancies::pluck('id')->toArray()),
            'total_price' => 0,
            'total_pax' => $this->faker->numberBetween(1, 10),
            'start_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'status' => $this->faker->randomElement(['Pending', 'Confirmed', 'Cancelled']),
            'is_hotel_requested' => $this->faker->boolean(),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function ($booking) {
            // Create random booking_cabin entries
            $cabins = Cabin::inRandomOrder()->limit(rand(1, 3))->get();
            foreach ($cabins as $cabin) {
                $booking->cabin()->attach($cabin->id);
            }

            // Create random booking_boat entries
            $boats = Boat::inRandomOrder()->limit(rand(1, 2))->get();
            foreach ($boats as $boat) {
                $booking->boat()->attach($boat->id);
            }

            // Create random booking_fees entries
            $additionalFees = AdditionalFee::inRandomOrder()->limit(rand(1, 4))->get();
            foreach ($additionalFees as $additionalFee) {
                $booking->additionalFees()->attach($additionalFee->id, [
                    'total_price' => $this->faker->numberBetween(50000, 1000000)
                ]);
            }
        });
    }
}
