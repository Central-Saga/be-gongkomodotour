<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trips;
use App\Models\Itineraries;
use App\Models\FlightSchedule;
use App\Models\TripDuration;
use App\Models\TripPrices;
use App\Models\AdditionalFee;

class TripsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Sailing Komodo Tour
        $sailingKomodoTrip = Trips::create([
            'name' => 'Sailing Komodo Tour',
            'include' => 'Charter Boat, Hotel Accommodation, Transportation, Local Guide, Snorkeling Equipment, Meals, Mineral Water, Flight Ticket, Entrance Fee, Insurance',
            'exclude' => 'Personal Expenses, Tipping, Soft Drinks & Alcohol, Documentation Fee',
            'note' => 'Valid for Low Season only; additional charges apply during High/Peak Seasons.',
            'start_time' => '08:00:00',
            'end_time' => '18:00:00',
            'meeting_point' => 'Komodo Airport (LBJ)',
            'type' => 'Open Trip',
            'status' => 'Aktif',
            'is_highlight' => 'Yes',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Itineraries for SKT-01 2D1N-A
        $sailingItineraries = [
            ['day_number' => 1, 'activities' => 'Komodo Airport - Komodo Island - Padar Island (Lunch / Dinner)'],
            ['day_number' => 2, 'activities' => 'Siaba Island - Labuan Bajo - Komodo Airport (Breakfast / Lunch)'],
        ];
        foreach ($sailingItineraries as $itinerary) {
            Itineraries::create(array_merge($itinerary, ['trip_id' => $sailingKomodoTrip->id]));
        }

        // Flight Schedule for SKT-01 2D1N-A
        FlightSchedule::create([
            'trip_id' => $sailingKomodoTrip->id,
            'route' => 'DPS - LBJ',
            'eta_time' => '08:25:00',
            'eta_text' => 'Tiba di Komodo Airport pukul 08:25',
            'etd_time' => '07:15:00',
            'etd_text' => 'Berangkat dari DPS pukul 07:15',
        ]);

        // Trip Duration and Prices for SKT-01 2D1N-A
        $sailingDuration = TripDuration::create([
            'trip_id' => $sailingKomodoTrip->id,
            'duration_label' => '2 Hari 1 Malam - A',
            'duration_days' => 2,
            'duration_nights' => 1,
            'status' => 'Aktif',
        ]);

        $sailingPrices = [
            ['pax_min' => 1, 'pax_max' => 1, 'price_per_pax' => 18350000],
            ['pax_min' => 2, 'pax_max' => 3, 'price_per_pax' => 9750000],
            ['pax_min' => 4, 'pax_max' => 5, 'price_per_pax' => 5550000],
            ['pax_min' => 6, 'pax_max' => 7, 'price_per_pax' => 4750000],
            ['pax_min' => 8, 'pax_max' => 9, 'price_per_pax' => 4500000],
            ['pax_min' => 10, 'pax_max' => 999, 'price_per_pax' => 4250000],
        ];
        foreach ($sailingPrices as $price) {
            TripPrices::create(array_merge($price, ['trip_duration_id' => $sailingDuration->id, 'status' => 'Aktif']));
        }

        // SKT-01 2D1N-B
        $sailingDuration2 = TripDuration::create([
            'trip_id' => $sailingKomodoTrip->id,
            'duration_label' => '2 Hari 1 Malam - B',
            'duration_days' => 2,
            'duration_nights' => 1,
            'status' => 'Aktif',
        ]);

        $sailingPrices2 = [
            ['pax_min' => 1, 'pax_max' => 1, 'price_per_pax' => 11650000],
            ['pax_min' => 2, 'pax_max' => 3, 'price_per_pax' => 6650000],
            ['pax_min' => 4, 'pax_max' => 5, 'price_per_pax' => 4900000],
            ['pax_min' => 6, 'pax_max' => 7, 'price_per_pax' => 4100000],
            ['pax_min' => 8, 'pax_max' => 9, 'price_per_pax' => 3900000],
            ['pax_min' => 10, 'pax_max' => 999, 'price_per_pax' => 3700000],
        ];
        foreach ($sailingPrices2 as $price) {
            TripPrices::create(array_merge($price, ['trip_duration_id' => $sailingDuration2->id, 'status' => 'Aktif']));
        }

        // 2. Stunning Flores Overland
        $floresTrip = Trips::create([
            'name' => 'Stunning Flores Overland',
            'include' => 'Transportation, Local Guide, Entrance Fee, Parking Fee, Hotel Accommodation, Mineral Water, Meals, Flight Ticket, Insurance',
            'exclude' => 'Personal Expenses, Tipping, Soft Drinks & Alcohol, Documentation Fee',
            'note' => 'Valid for Low Season only; additional charges apply during High/Peak Seasons.',
            'start_time' => '08:00:00',
            'end_time' => '18:00:00',
            'meeting_point' => 'Ende Airport',
            'type' => 'Open Trip',
            'status' => 'Aktif',
            'is_highlight' => 'Yes',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Itineraries for SFO-01 3D2N
        $floresItineraries = [
            ['day_number' => 1, 'activities' => 'Ende Airport - Kota Raja Beach Ende - Moni - Check in Hotel (Lunch / Dinner)'],
            ['day_number' => 2, 'activities' => 'Hotel - Kelimutu Lake - Nduaria Fruit Market - Wologae Village - Ende - Check in Hotel (Breakfast / Lunch / Dinner)'],
            ['day_number' => 3, 'activities' => 'Check out Hotel - Ende Airport (Breakfast)'],
        ];
        foreach ($floresItineraries as $itinerary) {
            Itineraries::create(array_merge($itinerary, ['trip_id' => $floresTrip->id]));
        }

        // Flight Schedule for SFO-01 3D2N
        FlightSchedule::create([
            'trip_id' => $floresTrip->id,
            'route' => 'DPS - ENDE',
            'eta_time' => '14:10:00',
            'eta_text' => 'Tiba di Ende pukul 14:10',
            'etd_time' => '08:00:00',
            'etd_text' => 'Berangkat dari DPS pukul 08:00',
        ]);

        // Trip Duration and Prices for SFO-01 3D2N
        $floresDuration = TripDuration::create([
            'trip_id' => $floresTrip->id,
            'duration_label' => '3 Hari 2 Malam',
            'duration_days' => 3,
            'duration_nights' => 2,
            'status' => 'Aktif',
        ]);

        $floresPrices = [
            ['pax_min' => 1, 'pax_max' => 1, 'price_per_pax' => 10450000],
            ['pax_min' => 2, 'pax_max' => 3, 'price_per_pax' => 6600000],
            ['pax_min' => 4, 'pax_max' => 5, 'price_per_pax' => 5700000],
            ['pax_min' => 6, 'pax_max' => 7, 'price_per_pax' => 4900000],
            ['pax_min' => 8, 'pax_max' => 9, 'price_per_pax' => 4700000],
            ['pax_min' => 10, 'pax_max' => 999, 'price_per_pax' => 4500000],
        ];
        foreach ($floresPrices as $price) {
            TripPrices::create(array_merge($price, ['trip_duration_id' => $floresDuration->id, 'status' => 'Aktif']));
        }

        // 3. Combination Stunning Flores & Sailing Komodo
        $comboTrip = Trips::create([
            'name' => 'Combination Stunning Flores & Sailing Komodo',
            'include' => 'Transportation, Local Guide, Entrance Fee, Charter Boat, Snorkeling Equipment, Hotel Accommodation, Mineral Water, Meals, Flight Ticket, Insurance',
            'exclude' => 'Personal Expenses, Tipping, Soft Drinks & Alcohol, Documentation Fee',
            'note' => 'Valid for Low Season only; additional charges apply during High/Peak Seasons.',
            'start_time' => '08:00:00',
            'end_time' => '18:00:00',
            'meeting_point' => 'Ende Airport',
            'type' => 'Open Trip',
            'status' => 'Aktif',
            'is_highlight' => 'Yes',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Itineraries for COMB-01 6D5N
        $comboItineraries = [
            ['day_number' => 1, 'activities' => 'Ende Airport - Kota Raja Beach Ende - Moni - Check in Hotel (Lunch / Dinner)'],
            ['day_number' => 2, 'activities' => 'Hotel - Kelimutu Lake - Nduaria Fruit Market - Blue Stone Beach - Bajawa - Check in Hotel (Breakfast / Lunch / Dinner)'],
            ['day_number' => 3, 'activities' => 'Check out Hotel - Bena Village - Aimere - Ruteng - Check in Hotel (Breakfast / Lunch / Dinner)'],
            ['day_number' => 4, 'activities' => 'Check out Hotel - Cancar Spider Rice Field - Labuan Bajo - Sylvia Hill - Check in Hotel (Breakfast / Lunch / Dinner)'],
            ['day_number' => 5, 'activities' => 'Check out Hotel - Harbor - Padar - Long Pink Beach - Komodo - Siaba (Breakfast / Lunch / Dinner)'],
            ['day_number' => 6, 'activities' => 'Bidadari - Labuan Bajo - Komodo Airport (Breakfast / Lunch)'],
        ];
        foreach ($comboItineraries as $itinerary) {
            Itineraries::create(array_merge($itinerary, ['trip_id' => $comboTrip->id]));
        }

        // Flight Schedule for COMB-01 6D5N
        FlightSchedule::create([
            'trip_id' => $comboTrip->id,
            'route' => 'DPS - ENDE',
            'eta_time' => '14:10:00',
            'eta_text' => 'Tiba di Ende pukul 14:10',
            'etd_time' => '08:00:00',
            'etd_text' => 'Berangkat dari DPS pukul 08:00',
        ]);

        // Trip Duration and Prices for COMB-01 6D5N
        $comboDuration = TripDuration::create([
            'trip_id' => $comboTrip->id,
            'duration_label' => '6 Hari 5 Malam',
            'duration_days' => 6,
            'duration_nights' => 5,
            'status' => 'Aktif',
        ]);

        $comboPrices = [
            ['pax_min' => 1, 'pax_max' => 1, 'price_per_pax' => 33800000],
            ['pax_min' => 2, 'pax_max' => 3, 'price_per_pax' => 19000000],
            ['pax_min' => 4, 'pax_max' => 5, 'price_per_pax' => 12300000],
            ['pax_min' => 6, 'pax_max' => 7, 'price_per_pax' => 10900000],
            ['pax_min' => 8, 'pax_max' => 9, 'price_per_pax' => 9650000],
            ['pax_min' => 10, 'pax_max' => 999, 'price_per_pax' => 8950000],
        ];
        foreach ($comboPrices as $price) {
            TripPrices::create(array_merge($price, ['trip_duration_id' => $comboDuration->id, 'status' => 'Aktif']));
        }

        // 4. Additional Fees (Linked to trips with Komodo National Park)
        $additionalFees = [
            [
                'trip_id' => $sailingKomodoTrip->id, // Linked to Sailing Komodo Tour
                'fee_category' => 'Entrance Fee Komodo National Park (Overseas)',
                'price' => 350000,
                'region' => 'Overseas',
                'unit' => 'per_pax',
                'pax_min' => 1,
                'pax_max' => 999,
                'day_type' => 'Weekday',
                'is_required' => true,
                'status' => 'Aktif',
            ],
            [
                'trip_id' => $sailingKomodoTrip->id, // Linked to Sailing Komodo Tour
                'fee_category' => 'Entrance Fee Komodo National Park (Overseas)',
                'price' => 450000,
                'region' => 'Overseas',
                'unit' => 'per_pax',
                'pax_min' => 1,
                'pax_max' => 999,
                'day_type' => 'Weekend',
                'is_required' => true,
                'status' => 'Aktif',
            ],
            [
                'trip_id' => $sailingKomodoTrip->id, // Linked to Sailing Komodo Tour
                'fee_category' => 'Ranger Fee Komodo National Park',
                'price' => 120000,
                'region' => 'Domestic & Overseas',
                'unit' => 'per_day',
                'pax_min' => 1,
                'pax_max' => 5,
                'day_type' => 'Weekday',
                'is_required' => true,
                'status' => 'Aktif',
            ],
            [
                'trip_id' => $comboTrip->id, // Linked to Combination Tour
                'fee_category' => 'Entrance Fee Komodo National Park (Overseas)',
                'price' => 350000,
                'region' => 'Overseas',
                'unit' => 'per_pax',
                'pax_min' => 1,
                'pax_max' => 999,
                'day_type' => 'Weekday',
                'is_required' => true,
                'status' => 'Aktif',
            ],
            [
                'trip_id' => $comboTrip->id, // Linked to Combination Tour
                'fee_category' => 'Entrance Fee Komodo National Park (Overseas)',
                'price' => 450000,
                'region' => 'Overseas',
                'unit' => 'per_pax',
                'pax_min' => 1,
                'pax_max' => 999,
                'day_type' => 'Weekend',
                'is_required' => true,
                'status' => 'Aktif',
            ],
            [
                'trip_id' => $comboTrip->id, // Linked to Combination Tour
                'fee_category' => 'Ranger Fee Komodo National Park',
                'price' => 120000,
                'region' => 'Domestic & Overseas',
                'unit' => 'per_day',
                'pax_min' => 1,
                'pax_max' => 5,
                'day_type' => 'Weekday',
                'is_required' => true,
                'status' => 'Aktif',
            ],
        ];
        foreach ($additionalFees as $fee) {
            AdditionalFee::create($fee);
        }

        // Optional: Generate additional fake trips using factory
        Trips::factory()->count(10)->create();
    }
}
