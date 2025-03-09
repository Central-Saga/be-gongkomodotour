<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Booking;
use App\Models\BankAccount;
use App\Models\HotelRequest;
use App\Models\Surcharge;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'booking_id' => $this->faker->randomElement(Booking::pluck('id')->toArray()),
            'bank_account_id' => $this->faker->randomElement(BankAccount::pluck('id')->toArray()),
            'total_amount' => $this->faker->numberBetween(1000000, 10000000),
            'payment_status' => $this->faker->randomElement(['Menunggu Pembayaran', 'Lunas', 'Ditolak']),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($transaction) {
            // Membuat 1-2 detail transaction untuk hotel request
            $hotelRequests = HotelRequest::inRandomOrder()->limit(rand(1, 2))->get();
            foreach ($hotelRequests as $hotelRequest) {
                $transaction->details()->create([
                    'amount' => $this->faker->numberBetween(500000, 2000000),
                    'description' => 'Payment for Hotel Request',
                    'reference_id' => $hotelRequest->id,
                    'reference_type' => HotelRequest::class,
                    'type' => 'Additional Fee'
                ]);
            }

            // Membuat 1-3 detail transaction untuk surcharge
            $surcharges = Surcharge::inRandomOrder()->limit(rand(1, 3))->get();
            foreach ($surcharges as $surcharge) {
                $transaction->details()->create([
                    'amount' => $this->faker->numberBetween(100000, 500000),
                    'description' => 'Payment for Surcharge',
                    'reference_id' => $surcharge->id,
                    'reference_type' => Surcharge::class,
                    'type' => 'Surcharge'
                ]);
            }
        });
    }
}
