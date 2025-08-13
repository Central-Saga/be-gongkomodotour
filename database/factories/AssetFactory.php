<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use App\Models\Gallery;
use App\Models\Cabin;
use App\Models\Boat;
use App\Models\Blog;
use App\Models\Transaction;
use App\Models\Carousel;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asset>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Daftar kelas model yang mungkin untuk asset polymorphic
        $assetTypes = [
            Gallery::class,
            Cabin::class,
            Boat::class,
            Blog::class,
            Transaction::class,
            Carousel::class,
        ];

        // Pilih salah satu tipe secara acak
        $selectedType = Arr::random($assetTypes);

        // Buat instance dari model yang terpilih menggunakan factory masing-masing
        $assetable = $selectedType::factory()->create();

        // Generate Unsplash URL dengan tema yang sesuai
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
        $unsplashUrl = "https://source.unsplash.com/random/1200x600?{$randomKeyword}";

        return [
            'title'         => $this->faker->sentence,
            'description'   => $this->faker->paragraph,
            'file_path'     => $unsplashUrl, // Gunakan Unsplash
            'file_url'      => $unsplashUrl, // Gunakan Unsplash juga
            'is_external'   => true, // Set sebagai external karena menggunakan URL
            // Setter untuk relasi polymorphic
            'assetable_id'   => $assetable->id,
            'assetable_type' => $assetable->getMorphClass(),
        ];
    }
}
