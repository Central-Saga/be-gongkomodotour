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
                'fee_category' => 'Tiket Masuk',
                'price' => 1000,
                'region' => 'Domestic',
                'unit' => 'per_day',
                'is_required' => 1,
                'pax_min' => 1,
                'pax_max' => 50,
                'day_type' => 'Weekday',
                'status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trip_id' => 1,
                'fee_category' => 'Transfer',
                'price' => 5000,
                'region' => 'Domestic',
                'unit' => 'per_5pax',
                'is_required' => 0,
                'pax_min' => 1,
                'pax_max' => 50,
                'day_type' => 'Weekday',
                'status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trip_id' => 1,
                'fee_category' => 'Parkir',
                'price' => 10000,
                'region' => 'Overseas',
                'unit' => 'per_pax',
                'is_required' => 0,
                'pax_min' => 1,
                'pax_max' => 50,
                'day_type' => 'Weekend',
                'status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trip_id' => 1,
                'fee_category' => 'Tiket Masuk',
                'price' => 10000,
                'region' => 'Domestic',
                'unit' => 'per_day',
                'is_required' => 1,
                'pax_min' => 1,
                'pax_max' => 50,
                'day_type' => 'Weekend',
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
