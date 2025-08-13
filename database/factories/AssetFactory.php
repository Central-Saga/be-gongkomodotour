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

        // Gunakan TravelImageFactory untuk generate gambar yang reliable
        $travelImageFactory = new TravelImageFactory($this->faker);
        $imageUrl = $travelImageFactory->generateTravelImage();

        return [
            'title'         => $this->faker->sentence,
            'description'   => $this->faker->paragraph,
            'file_path'     => $imageUrl,
            'file_url'      => $imageUrl,
            'is_external'   => true, // Set sebagai external karena menggunakan URL
            // Setter untuk relasi polymorphic
            'assetable_id'   => $assetable->id,
            'assetable_type' => $assetable->getMorphClass(),
        ];
    }

    /**
     * Buat asset untuk carousel tertentu
     */
    public function forCarousel($carousel)
    {
        return $this->state(function (array $attributes) use ($carousel) {
            // Gunakan TravelImageFactory untuk generate gambar yang reliable
            $travelImageFactory = new TravelImageFactory($this->faker);
            $imageUrl = $travelImageFactory->generateTravelImage();

            return [
                'assetable_id' => $carousel->id,
                'assetable_type' => get_class($carousel),
                'file_path' => $imageUrl,
                'file_url' => $imageUrl,
                'is_external' => true,
            ];
        });
    }
}
