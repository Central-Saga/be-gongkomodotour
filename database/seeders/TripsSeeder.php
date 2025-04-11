<?php

namespace Database\Seeders;

use App\Models\Trips;
use App\Models\Surcharge;
use App\Models\TripPrices;
use App\Models\Itineraries;
use App\Models\TripDuration;
use App\Models\AdditionalFee;
use App\Models\FlightSchedule;
use App\Models\Asset;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TripsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dapatkan semua nama file gambar dari folder
        $imageFiles = glob(public_path('img/wisata/*.{jpg,jpeg}'), GLOB_BRACE);

        // Buat direktori trip jika belum ada
        if (!Storage::exists('public/trip')) {
            Storage::makeDirectory('public/trip');
        }

        // Generate 4 highlighted trips with related data
        Trips::factory()
            ->count(4)
            ->highlighted()
            ->has(FlightSchedule::factory()->count(1))
            ->has(Itineraries::factory()->count(3))
            ->has(
                TripDuration::factory()
                    ->count(1)
                    ->has(TripPrices::factory()->count(6))
            )
            ->has(AdditionalFee::factory()->count(2)) // 2 additional fees per trip
            ->has(Surcharge::factory()->count(1)) // 1 surcharge per trip
            ->create()
            ->each(function ($trip) use ($imageFiles) {
                // Pilih 10 gambar secara acak
                $selectedImages = collect($imageFiles)->random(10);

                // Simpan informasi gambar ke database
                foreach ($selectedImages as $image) {
                    $storagePath = 'trip/' . basename($image);

                    if (file_exists($image)) {
                        Log::info('Copying image from ' . $image . ' to ' . $storagePath);
                        Storage::disk('public')->put($storagePath, file_get_contents($image));

                        Asset::create([
                            'title' => 'Trip Image',
                            'description' => 'Image for trip',
                            'file_path' => 'public/' . $storagePath,
                            'file_url' => Storage::url($storagePath),
                            'assetable_id' => $trip->id,
                            'assetable_type' => $trip->getMorphClass(),
                        ]);
                    } else {
                        Log::warning('Image not found: ' . $image);
                    }
                }
            });

        // Generate 8 non-highlighted trips with related data
        Trips::factory()
            ->count(8)
            ->has(FlightSchedule::factory()->count(1))
            ->has(Itineraries::factory()->count(3))
            ->has(
                TripDuration::factory()
                    ->count(1)
                    ->has(TripPrices::factory()->count(6))
            )
            ->has(AdditionalFee::factory()->count(2)) // 2 additional fees per trip
            ->has(Surcharge::factory()->count(1)) // 1 surcharge per trip
            ->create()
            ->each(function ($trip) use ($imageFiles) {
                // Pilih 10 gambar secara acak
                $selectedImages = collect($imageFiles)->random(10);

                // Simpan informasi gambar ke database
                foreach ($selectedImages as $image) {
                    $storagePath = 'trip/' . basename($image);

                    if (file_exists($image)) {
                        Log::info('Copying image from ' . $image . ' to ' . $storagePath);
                        Storage::disk('public')->put($storagePath, file_get_contents($image));

                        Asset::create([
                            'title' => 'Trip Image',
                            'description' => 'Image for trip',
                            'file_path' => 'public/' . $storagePath,
                            'file_url' => Storage::url($storagePath),
                            'assetable_id' => $trip->id,
                            'assetable_type' => $trip->getMorphClass(),
                        ]);
                    } else {
                        Log::warning('Image not found: ' . $image);
                    }
                }
            });
    }
}
