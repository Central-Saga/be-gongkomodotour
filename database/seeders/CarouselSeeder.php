<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Carousel;

class CarouselSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Opsi 1: Mixed sources (default, paling reliable)
        \App\Models\Carousel::factory()->count(3)->withAssets(2)->create();

        // Opsi 2: Unsplash only (bisa error jika API bermasalah)
        \App\Models\Carousel::factory()->count(2)->withUnsplashAssets(2)->create();

        // Opsi 3: Dari sumber tertentu
        \App\Models\Carousel::factory()->count(2)->withAssetsFromSource(2, 'picsum')->create();
        \App\Models\Carousel::factory()->count(2)->withAssetsFromSource(2, 'unsplash')->create();

        echo "✅ Carousel seeded successfully!\n";
        echo "   - 3 carousel dengan mixed sources\n";
        echo "   - 2 carousel dengan Unsplash only\n";
        echo "   - 2 carousel dengan Picsum only\n";
        echo "   - 2 carousel dengan Unsplash only (alternative method)\n";
    }
}
