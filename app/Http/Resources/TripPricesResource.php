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
            'price' => $this->price,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),

            // Relasi dengan trip_duration
            'trip_duration' => $this->whenLoaded('tripDuration', function () {
                return [
                    'id' => $this->tripDuration->id,
                    'duration_label' => $this->tripDuration->duration_label,
                    'duration_days' => $this->tripDuration->duration_days,
                    'status' => $this->tripDuration->status,
                ];
            }),
        ];
    }
}
