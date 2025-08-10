<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogFactory extends Factory
{
    protected $model = Blog::class;

    public function definition()
    {
        return [
            'author_id' => User::factory(),
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraphs(3, true),
            'category' => $this->faker->randomElement(['travel', 'tips']),
            'status' => $this->faker->randomElement(['draft', 'published']),
        ];
    }
}
