<?php
// filepath: /c:/laragon/www/be-gongkomodotour/database/factories/TestimonialFactory.php

namespace Database\Factories;

use App\Models\Customers;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestimonialFactory extends Factory
{
    protected $model = Testimonial::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id'  => Customers::inRandomOrder()->value('id') ?? Customers::factory(),
            'rating'       => $this->faker->numberBetween(1, 5),
            'review'       => $this->faker->paragraph,
            'is_approved'  => $this->faker->boolean(50),
            'is_highlight' => $this->faker->boolean(30),
        ];
    }
}