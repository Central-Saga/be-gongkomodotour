<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AdditionalFee;

class AdditionalFeeFactory extends Factory
{
    protected $model = AdditionalFee::class;

    public function definition(): array
    {
        $feeCategories = [
            'Entrance Fee National Park',
            'Ranger Fee',
            'Conservation Fee',
            'Guide Fee',
            'Equipment Rental Fee',
        ];

        $regions = ['Domestic', 'Overseas', 'Domestic & Overseas'];
        $units = ['per_pax', 'per_5pax', 'per_day', 'per_day_guide'];
        $dayTypes = ['Weekday', 'Weekend', null];

        return [
            'trip_id' => \App\Models\Trips::factory(),
            'fee_category' => $this->faker->randomElement($feeCategories),
            'price' => $this->faker->randomNumber(5, true) * 1000, // e.g., 150000, 250000
            'region' => $this->faker->randomElement($regions),
            'unit' => $this->faker->randomElement($units),
            'pax_min' => $this->faker->numberBetween(1, 5),
            'pax_max' => $this->faker->randomElement([5, 10, 999]),
            'day_type' => $this->faker->randomElement($dayTypes),
            'is_required' => $this->faker->boolean(80), // 80% chance of being required
            'status' => 'Aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
