<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\BookingResource;
use App\Http\Resources\DetailTransactionResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AssetResource;

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
            'bank_type' => $this->bank_type,
            'total_amount' => $this->total_amount,
            'payment_status' => $this->payment_status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),

            'booking' => $this->whenLoaded('booking', function () {
                return BookingResource::make($this->booking);
            }),

            'details' => $this->whenLoaded('details', function () {
                return DetailTransactionResource::collection($this->details);
            }),

            'assets' => $this->whenLoaded('assets', function () {
                return AssetResource::collection($this->assets);
            }),
        ];
    }
}
