<?php

namespace Database\Factories;

use App\Models\Trips;
use App\Models\Itineraries;
use App\Models\FlightSchedule;
use App\Models\TripDuration;
use App\Models\TripPrices;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trips>
 */
class TripsFactory extends Factory
{
    protected $model = Trips::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'include' => $this->faker->paragraph(),
            'exclude' => $this->faker->paragraph(),
            'note' => $this->faker->sentence(),
            'duration' => $this->faker->randomElement(['1 day', '2 days', '3 days']),
            'start_time' => $this->faker->time('H:i:s'),
            'end_time' => $this->faker->time('H:i:s'),
            'meeting_point' => $this->faker->address(),
            'type' => $this->faker->randomElement(['Open Trip', 'Private Trip']),
            'status' => $this->faker->randomElement(['Aktif', 'Non Aktif']),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Trips $trip) {
            // Membuat 3 itineraries untuk trip tersebut
            Itineraries::factory()->count(3)->create([
                'trip_id' => $trip->id,
            ]);

            // Membuat 2 flight schedule untuk trip tersebut
            FlightSchedule::factory()->count(2)->create([
                'trip_id' => $trip->id,
            ]);

            // Membuat 2 trip durations untuk trip tersebut
            // dan untuk tiap trip duration, buat 2 trip prices
            TripDuration::factory()->count(2)->create([
                'trip_id' => $trip->id,
            ])->each(function ($tripDuration) {
                TripPrices::factory()->count(2)->create([
                    'trip_duration_id' => $tripDuration->id,
                ]);
            });
        });
    }
}
