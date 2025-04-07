<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Itineraries;

class ItinerariesFactory extends Factory
{
    protected $model = Itineraries::class;

    public function definition(): array
    {
        $activities = [
            'Visit Local Market - Cultural Village Tour - Lunch',
            'Hiking to Waterfall - Snorkeling - Dinner',
            'City Sightseeing - Museum Visit - Evening Market',
            'Trekking to Volcano - Photography Session - Lunch',
            'Beach Relaxation - Boat Trip - Sunset Viewing',
        ];

        return [
            'trip_id' => null, // Will be set by relationship
            'day_number' => $this->faker->numberBetween(1, 6),
            'activities' => $this->faker->randomElement($activities) . ' (Breakfast / Lunch)',
        ];
    }
}
