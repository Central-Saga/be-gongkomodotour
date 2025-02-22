<?php

namespace Database\Factories;

use App\Models\DetailTransaction;
use App\Models\Surcharge;
use App\Models\HotelRequest;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetailTransaction>
 */
class DetailTransactionFactory extends Factory
{
    protected $model = DetailTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $referenceTypes = ['Surcharge', 'HotelRequest'];
        $selectedReferenceType = $this->faker->randomElement($referenceTypes);

        $type = $selectedReferenceType === 'Surcharge' ? 'Surcharge' : 'Additional Fee';

        return [
            'transaction_id' => Transaction::factory(),
            'type'           => $type,
            'amount'         => $this->faker->randomFloat(2, 100, 1000),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (DetailTransaction $detailTransaction) {
            // Berdasarkan nilai 'type' kita pilih model referensi yang sesuai
            if ($detailTransaction->type === 'Surcharge') {
                $reference = Surcharge::factory()->create();
            } else {
                $reference = HotelRequest::factory()->create();
            }
            // Kaitkan model referensi ke DetailTransaction melalui polymorphic relationship
            $detailTransaction->reference()->associate($reference);
            $detailTransaction->save();
        });
    }
}
