<?php

namespace Database\Factories;

use App\Models\Subscriber;
use App\Models\Customers;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriberFactory extends Factory
{
    protected $model = Subscriber::class;

    public function definition()
    {
        return [
            'customer_id' => Customers::factory(),
            'email' => $this->faker->unique()->safeEmail,
            'name' => $this->faker->name,
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
