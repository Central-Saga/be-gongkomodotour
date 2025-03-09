<?php

namespace Database\Factories;

use App\Models\Boat;
use Illuminate\Database\Eloquent\Factories\Factory;

class BoatFactory extends Factory {
    protected $model = Boat::class;

    public function definition() {
        return [
            'boat_name' => $this->faker->company . ' Boat',
            'spesification' => $this->faker->paragraph,
            'cabin_information' => $this->faker->sentence,
            'facilities' => $this->faker->sentence,
            'status' => $this->faker->randomElement(array: ['Aktif','Non Aktif']),
        ];
    }
}

