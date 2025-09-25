<?php

namespace Database\Factories;

use App\Models\HotelOccupancies;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HotelOccupancies>
 */
class HotelOccupanciesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HotelOccupancies::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hotel_name' => $this->faker->randomElement([
                'Hotel Santika',
                'Grand Mercure',
                'The Jayakarta',
                'Aston Hotel',
                'Swiss-Belinn',
                'Ibis Hotel',
                'Novotel',
                'Pullman Hotel',
                'Harris Hotel',
                'Fave Hotel'
            ]),
            'hotel_type' => $this->faker->randomElement(['Luxury', 'Budget', 'Boutique']),
            'occupancy' => $this->faker->randomElement([
                'Single Occupancy', 
                'Double Occupancy', 
                'Twin Occupancy', 
                'Triple Occupancy', 
                'Quad Occupancy',
                'Family Room',
                'Suite',
                'Deluxe Room'
            ]),
            'price' => $this->faker->randomFloat(2, 50, 500),
            'status' => $this->faker->randomElement(array: ['Aktif', 'Non Aktif']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
