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
            // Daftar cabin yang akan dibuat
            $cabins = [
                [
                    'cabin_name' => 'Master Suite',
                    'bed_type' => 'king',
                    'min_pax' => 2,
                    'max_pax' => 3,
                    'base_price' => 2500000,
                    'additional_price' => 800000,
                    'status' => 'Aktif'
                ],
                [
                    'cabin_name' => 'Deluxe Cabin',
                    'bed_type' => 'queen',
                    'min_pax' => 2,
                    'max_pax' => 3,
                    'base_price' => 1800000,
                    'additional_price' => 600000,
                    'status' => 'Aktif'
                ],
                [
                    'cabin_name' => 'Family Cabin',
                    'bed_type' => 'double',
                    'min_pax' => 3,
                    'max_pax' => 4,
                    'base_price' => 2000000,
                    'additional_price' => 700000,
                    'status' => 'Aktif'
                ],
                [
                    'cabin_name' => 'Standard Cabin',
                    'bed_type' => 'double',
                    'min_pax' => 2,
                    'max_pax' => 2,
                    'base_price' => 1200000,
                    'additional_price' => 400000,
                    'status' => 'Aktif'
                ],
                [
                    'cabin_name' => 'Twin Cabin',
                    'bed_type' => 'single',
                    'min_pax' => 2,
                    'max_pax' => 2,
                    'base_price' => 1000000,
                    'additional_price' => 300000,
                    'status' => 'Aktif'
                ],
                [
                    'cabin_name' => 'Single Cabin',
                    'bed_type' => 'single',
                    'min_pax' => 1,
                    'max_pax' => 1,
                    'base_price' => 800000,
                    'additional_price' => 0,
                    'status' => 'Aktif'
                ]
            ];

            // Buat 5-6 cabin untuk setiap boat
            $numberOfCabins = rand(5, 6);
            $selectedCabins = array_slice($cabins, 0, $numberOfCabins);

            foreach ($selectedCabins as $cabinData) {
                $cabin = Cabin::create([
                    'boat_id' => $boat->id,
                    ...$cabinData
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

                                // Buat asset untuk cabin
                                $cabin->assets()->create([
                                    'title' => "Cabin Image {$i}",
                                    'description' => "Image {$i} for {$cabin->cabin_name}",
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
}
