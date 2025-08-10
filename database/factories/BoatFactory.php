<?php

namespace Database\Factories;

use App\Models\Boat;
use Illuminate\Database\Eloquent\Factories\Factory;

class BoatFactory extends Factory
{
    protected $model = Boat::class;

    public function definition()
    {
        $boatTypes = ['Luxury', 'Deluxe', 'Standard'];
        $boatNames = ['Phinisi', 'Motorboat', 'Yacht'];

        return [
            'boat_name' => $this->faker->randomElement($boatNames) . ' ' . $this->faker->randomElement($boatTypes),
            'spesification' => "Length: " . rand(15, 40) . "m\nCapacity: " . rand(10, 30) . " persons\nCrew: " . rand(5, 15) . " persons",
            'cabin_information' => "Master Cabin: " . rand(1, 3) . "\nDeluxe Cabin: " . rand(2, 5) . "\nStandard Cabin: " . rand(2, 4),
            'facilities' => implode(", ", $this->faker->randomElements(['WiFi', 'AC', 'Restaurant', 'Diving Equipment', 'Snorkeling Gear', 'Sun Deck', 'Bar', 'Spa'], rand(4, 8))),
            'status' => $this->faker->randomElement(['Aktif', 'Non Aktif']),
        ];
    }
}
