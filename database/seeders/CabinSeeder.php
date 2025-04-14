<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cabin;
use App\Models\Boat;
use App\Models\Asset;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CabinSeeder extends Seeder
{
    public function run()
    {
        // Buat direktori cabin jika belum ada
        if (!Storage::exists('public/cabin')) {
            Storage::makeDirectory('public/cabin');
        }

        $boats = Boat::all();

        foreach ($boats as $boat) {
            // Cabin 1 untuk setiap boat
            $cabin1 = Cabin::create([
                'boat_id' => $boat->id,
                'cabin_name' => 'Deluxe Cabin',
                'bed_type' => 'queen',
                'min_pax' => 2,
                'max_pax' => 3,
                'base_price' => 1500000,
                'additional_price' => 500000,
                'status' => 'Aktif'
            ]);

            // Cabin 2 untuk setiap boat
            $cabin2 = Cabin::create([
                'boat_id' => $boat->id,
                'cabin_name' => 'Standard Cabin',
                'bed_type' => 'double',
                'min_pax' => 2,
                'max_pax' => 2,
                'base_price' => 1000000,
                'additional_price' => 300000,
                'status' => 'Aktif'
            ]);

            // Ambil gambar boat yang terkait
            $boatAssets = $boat->assets()->first();
            if ($boatAssets) {
                // Ambil nama file dari file_url
                $boatImageUrl = $boatAssets->file_url;
                $boatImageName = basename($boatImageUrl);
                $boatImagePath = "boat/{$boatImageName}";

                // Buat 3 gambar untuk setiap cabin
                for ($i = 1; $i <= 3; $i++) {
                    // Generate nama file baru untuk cabin
                    $newImageName = str_replace('.jpg', "_cabin{$i}.jpg", $boatImageName);
                    $storagePath = "cabin/{$newImageName}";

                    // Copy file dari boat ke cabin
                    if (Storage::disk('public')->exists($boatImagePath)) {
                        $sourcePath = storage_path('app/public/' . $boatImagePath);

                        Log::info('Copying cabin image from ' . $sourcePath . ' to ' . $storagePath);

                        // Pastikan file source ada
                        if (file_exists($sourcePath)) {
                            // Salin file ke storage public
                            Storage::disk('public')->put($storagePath, file_get_contents($sourcePath));

                            // Buat asset untuk cabin 1
                            $cabin1->assets()->create([
                                'title' => "Cabin Image {$i}",
                                'description' => "Image {$i} for Deluxe Cabin",
                                'file_path' => "public/" . $storagePath,
                                'file_url' => Storage::url($storagePath),
                                'is_external' => false
                            ]);

                            // Buat asset untuk cabin 2
                            $cabin2->assets()->create([
                                'title' => "Cabin Image {$i}",
                                'description' => "Image {$i} for Standard Cabin",
                                'file_path' => "public/" . $storagePath,
                                'file_url' => Storage::url($storagePath),
                                'is_external' => false
                            ]);
                        } else {
                            Log::warning('Source file not found: ' . $sourcePath);
                        }
                    } else {
                        Log::warning('Boat image not found in storage: ' . $boatImagePath);
                    }
                }
            } else {
                Log::warning('No assets found for boat: ' . $boat->id);
            }
        }
    }
}
