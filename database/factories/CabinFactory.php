<?php

namespace Database\Factories;

use App\Models\Cabin;
use App\Models\Boat;
use Illuminate\Database\Eloquent\Factories\Factory;

class CabinFactory extends Factory
{
    protected $model = Cabin::class;

    public function definition()
    {
        return [
            'boat_id' => Boat::factory(),
            'cabin_name' => 'Cabin ' . $this->faker->word,
            'bed_type' => $this->faker->randomElement([
                'Single Bed',
                'Double Bed', 
                'Queen Bed',
                'King Bed',
                'Twin Beds',
                'Bunk Beds',
                'Sofa Bed',
                'Murphy Bed',
                'Waterbed',
                'Futon'
            ]),
            'bathroom' => $this->faker->randomElement([
                'Private Bathroom',
                'Shared Bathroom',
                'En-suite Bathroom',
                'Jack and Jill Bathroom',
                'Master Bathroom',
                'Guest Bathroom',
                'Half Bathroom',
                'Full Bathroom',
                'Wet Room',
                'Powder Room'
            ]),
            'min_pax' => 1,
            'max_pax' => $this->faker->numberBetween(2, 6),
            'base_price' => $this->faker->randomFloat(2, 100, 2000),
            'additional_price' => $this->faker->randomFloat(2, 50, 500),
            'status' => $this->faker->randomElement(['Aktif', 'Non Aktif']),
        ];
    }
}
