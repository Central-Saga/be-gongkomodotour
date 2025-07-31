<?php

namespace Database\Seeders;

use App\Models\Trips;
use App\Models\Boat;
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

        // Dapatkan semua boat yang tersedia
        $boats = Boat::all();

        // Generate 2 highlighted trips with boat and 3 destinations
        Trips::factory()
            ->count(2)
            ->highlighted()
            ->withBoat($boats->random()->id)
            ->withHotel()
            ->withDestinationCount(3)
            ->has(FlightSchedule::factory()->count(1))
            ->has(
                TripDuration::factory()
                    ->count(1)
                    ->has(Itineraries::factory()->count(3))
                    ->has(TripPrices::factory()->count(6))
            )
            ->has(AdditionalFee::factory()->count(2))
            ->create()
            ->each(function ($trip) use ($imageFiles) {
                $this->createAssets($trip, $imageFiles);
            });

        // Generate 2 highlighted trips without boat and 2 destinations
        Trips::factory()
            ->count(2)
            ->highlighted()
            ->withoutBoat()
            ->withHotel()
            ->withDestinationCount(2)
            ->has(FlightSchedule::factory()->count(1))
            ->has(
                TripDuration::factory()
                    ->count(1)
                    ->has(Itineraries::factory()->count(3))
                    ->has(TripPrices::factory()->count(6))
            )
            ->has(AdditionalFee::factory()->count(2))
            ->create()
            ->each(function ($trip) use ($imageFiles) {
                $this->createAssets($trip, $imageFiles);
            });

        // Generate 4 non-highlighted trips with boat and random destinations (1-5)
        Trips::factory()
            ->count(4)
            ->withBoat($boats->random()->id)
            ->withHotel()
            ->has(FlightSchedule::factory()->count(1))
            ->has(
                TripDuration::factory()
                    ->count(1)
                    ->has(Itineraries::factory()->count(3))
                    ->has(TripPrices::factory()->count(6))
            )
            ->has(AdditionalFee::factory()->count(2))
            ->create()
            ->each(function ($trip) use ($imageFiles) {
                $this->createAssets($trip, $imageFiles);
            });

        // Generate 4 non-highlighted trips without boat and random destinations (1-5)
        Trips::factory()
            ->count(4)
            ->withoutBoat()
            ->withHotel()
            ->has(FlightSchedule::factory()->count(1))
            ->has(
                TripDuration::factory()
                    ->count(1)
                    ->has(Itineraries::factory()->count(3))
                    ->has(TripPrices::factory()->count(6))
            )
            ->has(AdditionalFee::factory()->count(2))
            ->create()
            ->each(function ($trip) use ($imageFiles) {
                $this->createAssets($trip, $imageFiles);
            });
    }

    private function createAssets($trip, $imageFiles)
    {
        $selectedImages = collect($imageFiles)->random(10);

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
    }
}
