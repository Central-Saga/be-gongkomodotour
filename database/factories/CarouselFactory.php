<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Asset;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Carousel>
 *
 * Factory untuk membuat Carousel dengan gambar dari Unsplash
 * Gambar carousel sekarang dihandle oleh model Asset (relasi polymorphic)
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
            // Buat asset dengan gambar yang reliable untuk carousel ini
            for ($i = 0; $i < $count; $i++) {
                Asset::create([
                    'assetable_id' => $carousel->id,
                    'assetable_type' => get_class($carousel),
                    'title' => $this->faker->sentence,
                    'description' => $this->faker->paragraph,
                    'file_path' => $this->generateImageUrl(),
                    'file_url' => $this->generateImageUrl(),
                    'is_external' => true,
                ]);
            }
        });
    }

    /**
     * Buat carousel dengan assets dari Unsplash only
     * ⚠️  Warning: Bisa error jika API rate limit atau down
     */
    public function withUnsplashAssets($count = 1)
    {
        return $this->afterCreating(function ($carousel) use ($count) {
            for ($i = 0; $i < $count; $i++) {
                $travelImageFactory = new \Database\Factories\TravelImageFactory($this->faker);
                $imageUrl = $travelImageFactory->generateUnsplashOnly();

                Asset::create([
                    'assetable_id' => $carousel->id,
                    'assetable_type' => get_class($carousel),
                    'title' => $this->faker->sentence,
                    'description' => $this->faker->paragraph,
                    'file_path' => $imageUrl,
                    'file_url' => $imageUrl,
                    'is_external' => true,
                ]);
            }
        });
    }

    /**
     * Buat carousel dengan assets dari sumber tertentu
     */
    public function withAssetsFromSource($count = 1, $source = 'mixed')
    {
        return $this->afterCreating(function ($carousel) use ($count, $source) {
            for ($i = 0; $i < $count; $i++) {
                $travelImageFactory = new \Database\Factories\TravelImageFactory($this->faker);
                $imageUrl = $travelImageFactory->generateImageWithPreference($source);

                Asset::create([
                    'assetable_id' => $carousel->id,
                    'assetable_type' => get_class($carousel),
                    'title' => $this->faker->sentence,
                    'description' => $this->faker->paragraph,
                    'file_path' => $imageUrl,
                    'file_url' => $imageUrl,
                    'is_external' => true,
                ]);
            }
        });
    }

    /**
     * Generate URL gambar yang reliable untuk carousel
     *
     * @return string URL gambar
     */
    private function generateImageUrl()
    {
        // Gunakan TravelImageFactory untuk generate gambar yang reliable
        $travelImageFactory = new \Database\Factories\TravelImageFactory($this->faker);
        return $travelImageFactory->generateTravelImage();
    }
}
