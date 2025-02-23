<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_id' => $this->booking_id,
            'bank_account_id' => $this->bank_account_id,
            'total_amount' => $this->total_amount,
            'payment_status' => $this->payment_status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),

            'booking' => $this->whenLoaded('booking', function () {
                return BookingResource::make($this->booking);
            }),

            'bank_account' => $this->whenLoaded('bankAccount', function () {
                return BankAccountResource::make($this->bankAccount);
            }),

            'details' => $this->whenLoaded('details', function () {
                return TransactionDetailResource::collection($this->details);
            }),
        ];
    }
}
