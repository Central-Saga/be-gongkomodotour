<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Surcharge;

class SurchargeFactory extends Factory
{
    protected $model = Surcharge::class;

    public function definition(): array
    {
        $seasons = ['High Season', 'Peak Season', 'Holiday Season'];
        $startDate = $this->faker->dateTimeBetween('now', '+6 months');
        $endDate = $this->faker->dateTimeBetween($startDate, '+6 months');

        return [
            'trip_id' => null, // Will be set by relationship
            'season' => $this->faker->randomElement($seasons),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'surcharge_price' => $this->faker->randomNumber(5, true) * 1000, // e.g., 500000, 750000
            'status' => 'Aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
