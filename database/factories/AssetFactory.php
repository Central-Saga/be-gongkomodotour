<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use App\Models\Gallery;
use App\Models\Cabin;
use App\Models\Boat;
use App\Models\Blog;
use App\Models\Transaction;

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
        ];

        // Pilih salah satu tipe secara acak
        $selectedType = Arr::random($assetTypes);

        // Buat instance dari model yang terpilih menggunakan factory masing-masing
        $assetable = $selectedType::factory()->create();

        return [
            'title'         => $this->faker->sentence,
            'description'   => $this->faker->paragraph,
            'file_path'     => $this->faker->imageUrl(),
            'file_url'      => $this->faker->url,
            // Setter untuk relasi polymorphic
            'assetable_id'   => $assetable->id,
            'assetable_type' => $assetable->getMorphClass(),
        ];
    }
}
