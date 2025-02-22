<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetailTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DetailTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DetailTransaction::factory()->count(10)->create();
    }
}
