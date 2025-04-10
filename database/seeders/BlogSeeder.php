<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BlogSeeder extends Seeder
{
    public function run()
    {
        Blog::factory()->count(10)->create()->each(function ($blog, $index) {
            for ($i = 1; $i <= 2; $i++) {
                $imageIndex = (($index * 2) + $i) % 10;
                $imageIndex = $imageIndex === 0 ? 10 : $imageIndex;
                $imagePath = "public/img/wisata/wisata-" . $imageIndex . ".jpg";
                $storagePath = "public/wisata-" . $imageIndex . ".jpg";

                if (file_exists($imagePath)) {
                    Log::info('Copying image from ' . $imagePath . ' to ' . $storagePath);
                    Storage::copy($imagePath, $storagePath);

                    $blog->assets()->create([
                        'title' => "Wisata Image " . $imageIndex,
                        'description' => "Image for blog post " . ($index + 1),
                        'file_path' => $storagePath,
                        'file_url' => Storage::url($storagePath),
                        'is_external' => false,
                    ]);
                } else {
                    Log::warning('Image not found: ' . $imagePath);
                }
            }
        });
    }
}
