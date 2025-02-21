<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailBlastRecipient;

class EmailBlastRecipientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 EmailBlastRecipient records
        EmailBlastRecipient::factory(10)->create();
    }
}