<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripPricesResource extends JsonResource
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
            'trip_duration_id' => $this->trip_duration_id,
            'pax_min' => $this->pax_min,
            'pax_max' => $this->pax_max,
            'price_per_pax' => $this->price_per_pax,
            'status' => $this->status,
            'region' => $this->region,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),

            // Relasi dengan trip_duration
            'trip_duration' => $this->whenLoaded('tripDuration', function () {
                return TripDurationResource::make($this->tripDuration);
            }),
        ];
    }
}
