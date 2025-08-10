<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TripDuration;

class TripDurationFactory extends Factory
{
    protected $model = TripDuration::class;

    public function definition(): array
    {
        $durations = [
            ['label' => '2 Hari 1 Malam', 'days' => 2, 'nights' => 1],
            ['label' => '3 Hari 2 Malam', 'days' => 3, 'nights' => 2],
            ['label' => '4 Hari 3 Malam', 'days' => 4, 'nights' => 3],
            ['label' => '5 Hari 4 Malam', 'days' => 5, 'nights' => 4],
        ];

        $duration = $this->faker->randomElement($durations);

        return [
            'trip_id' => null, // Will be set by relationship
            'duration_label' => $duration['label'],
            'duration_days' => $duration['days'],
            'duration_nights' => $duration['nights'],
            'status' => 'Aktif',
        ];
    }
}
