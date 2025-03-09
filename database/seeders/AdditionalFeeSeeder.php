<?php

namespace Database\Seeders;

use App\Models\AdditionalFee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdditionalFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $additionalFees = [
            [
                'trip_id' => 1,
                'fee_category' => 'Entrance Fee TNK',
                'price' => 200000,
                'region' => 'Domestic',
                'unit' => 'per_pax',
                'pax_min' => 1,
                'pax_max' => 1,
                'day_type' => 'Weekday',
                'is_required' => true,
                'status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trip_id' => 1,
                'fee_category' => 'Entrance Fee TNK',
                'price' => 250000,
                'region' => 'Domestic',
                'unit' => 'per_pax',
                'pax_min' => 1,
                'pax_max' => 1,
                'day_type' => 'Weekend',
                'is_required' => true,
                'status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trip_id' => 1,
                'fee_category' => 'Ranger Fee At Padar Island',
                'price' => 150000,
                'region' => 'Domestic & Overseas',
                'unit' => 'per_5pax',
                'pax_min' => 1,
                'pax_max' => 5,
                'is_required' => true,
                'status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trip_id' => 1,
                'fee_category' => 'Ranger Fee At Komodo Island',
                'price' => 200000,
                'region' => 'Domestic & Overseas',
                'unit' => 'per_5pax',
                'pax_min' => 1,
                'pax_max' => 5,
                'is_required' => true,
                'status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trip_id' => 1,
                'fee_category' => 'Entrance Fee TNK',
                'price' => 350000,
                'region' => 'Overseas',
                'unit' => 'per_pax',
                'pax_min' => 1,
                'pax_max' => 1,
                'day_type' => 'Weekday',
                'is_required' => true,
                'status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trip_id' => 1,
                'fee_category' => 'Entrance Fee TNK',
                'price' => 450000,
                'region' => 'Overseas',
                'unit' => 'per_pax',
                'pax_min' => 1,
                'pax_max' => 1,
                'day_type' => 'Weekend',
                'is_required' => true,
                'status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trip_id' => 1,
                'fee_category' => 'Additional English Guide',
                'price' => 650000,
                'region' => 'Overseas',
                'unit' => 'per_day_guide',
                'pax_min' => 1,
                'pax_max' => 5,
                'is_required' => false,
                'status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trip_id' => 1,
                'fee_category' => 'Additional English Guide',
                'price' => 850000,
                'region' => 'Overseas',
                'unit' => 'per_day_guide',
                'pax_min' => 6,
                'pax_max' => 10,
                'is_required' => false,
                'status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($additionalFees as $fee) {
            AdditionalFee::create($fee);
        }

        AdditionalFee::factory()->count(10)->create();
    }
}
