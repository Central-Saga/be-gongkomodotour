<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripDurationResource extends JsonResource
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
            'duration_label' => $this->duration_label,
            'duration_days' => $this->duration_days,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),

            // Relasi dengan trip
            'trip' => $this->whenLoaded('trip', function () {
                return TripResource::make($this->trip);
            }),

            'trip_prices' => $this->whenLoaded('tripPrices', function () {
                return TripPricesResource::collection($this->tripPrices);
            }),
        ];
    }
}
