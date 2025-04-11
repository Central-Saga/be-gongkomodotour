<?php

namespace Database\Seeders;

use App\Models\Gallery;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dapatkan semua nama file gambar dari folder
        $imageFiles = glob(public_path('img/wisata/*.{jpg,jpeg}'), GLOB_BRACE);

        // Buat direktori gallery jika belum ada
        if (!Storage::exists('public/gallery')) {
            Storage::makeDirectory('public/gallery');
        }

        Gallery::factory()->count(25)->create()->each(function ($gallery, $index) use ($imageFiles) {
            // Ambil satu gambar berdasarkan indeks
            $image = $imageFiles[$index % count($imageFiles)];

            $storagePath = 'gallery/' . basename($image);

            if (file_exists($image)) {
                // Log::info('Copying image from ' . $image . ' to ' . $storagePath);
                Storage::disk('public')->put($storagePath, file_get_contents($image));

                $gallery->assets()->create([
                    'title' => 'Gallery Image',
                    'description' => 'Image for gallery',
                    'file_path' => 'public/' . $storagePath,
                    'file_url' => Storage::url($storagePath),
                ]);
            } else {
                Log::warning('Image not found: ' . $image);
            }
        });
    }
}
