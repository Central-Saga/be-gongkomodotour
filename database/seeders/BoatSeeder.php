<?php

namespace Database\Seeders;

use App\Models\Boat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BoatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Buat direktori boat jika belum ada
        if (!Storage::exists('public/boat')) {
            Storage::makeDirectory('public/boat');
        }

        $boatImages = [
            'bg-boat-dlx-mv.jpg',
            'bg-luxury.jpg',
            'boat-alf.jpg',
            'boat-dlx-lmb2.jpg',
            'boat-dlx-mv.jpg',
            'boat-zm.jpg',
            'boat-zn-phinisi.jpg',
            'boat-zn-phinisi2.jpg',
            'luxury_phinisi.jpg'
        ];

        $imageTypes = [
            'Exterior View',
            'Deck View',
            'Cabin Interior',
            'Dining Area',
            'Sun Deck'
        ];

        Boat::factory(9)->create()->each(function ($boat, $index) use ($boatImages, $imageTypes) {
            $imageName = $boatImages[$index];
            $sourcePath = public_path("img/boat/{$imageName}");
            $storagePath = "boat/{$imageName}";

            if (file_exists($sourcePath)) {
                Log::info('Copying boat image from ' . $sourcePath . ' to ' . $storagePath);
                Storage::disk('public')->put($storagePath, file_get_contents($sourcePath));

                // Buat 5 asset untuk setiap boat
                foreach ($imageTypes as $type) {
                    $boat->assets()->create([
                        'title' => "Boat {$type}",
                        'description' => "{$type} of {$boat->boat_name}",
                        'file_path' => "public/" . $storagePath,
                        'file_url' => Storage::url($storagePath),
                        'is_external' => false,
                    ]);
                }
            } else {
                Log::warning('Boat image not found: ' . $sourcePath);
            }
        });
    }
}
