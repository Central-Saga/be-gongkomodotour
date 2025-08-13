<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Asset;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Carousel>
 *
 * Factory untuk membuat Carousel dengan gambar dari Unsplash
 *
 * Cara penggunaan:
 * 1. Carousel biasa: Carousel::factory()->create()
 * 2. Carousel dengan 1 asset: Carousel::factory()->withAssets()->create()
 * 3. Carousel dengan multiple assets: Carousel::factory()->withAssets(3)->create()
 */
class CarouselFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'link' => $this->faker->url,
            'order_num' => $this->faker->numberBetween(1, 10),
            'is_active' => true,
        ];
    }

    /**
     * Configure the factory to create carousel with assets.
     *
     * @param int $count Jumlah asset yang akan dibuat
     * @return $this
     */
    public function withAssets($count = 1)
    {
        return $this->afterCreating(function ($carousel) use ($count) {
            // Buat asset dengan gambar Unsplash untuk carousel ini
            for ($i = 0; $i < $count; $i++) {
                Asset::factory()->create([
                    'assetable_id' => $carousel->id,
                    'assetable_type' => Carousel::class,
                    'title' => $this->faker->sentence,
                    'description' => $this->faker->paragraph,
                    'file_path' => $this->generateUnsplashUrl(),
                    'file_url' => $this->generateUnsplashUrl(),
                    'is_external' => true,
                ]);
            }
        });
    }

    /**
     * Generate Unsplash URL dengan tema wisata
     *
     * @return string URL gambar Unsplash
     */
    private function generateUnsplashUrl()
    {
        $travelKeywords = [
            'boat,sea',
            'beach,ocean',
            'mountain,landscape',
            'tropical,island',
            'adventure,travel',
            'sunset,water',
            'nature,forest',
            'cultural,heritage'
        ];

        $randomKeyword = $this->faker->randomElement($travelKeywords);
        return "https://source.unsplash.com/random/1200x600?{$randomKeyword}";
    }
}
