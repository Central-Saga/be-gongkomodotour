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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Relasi dengan trip
            'trip' => $this->whenLoaded('trip', function() {
                return [
                    'id' => $this->trip->id,
                    'name' => $this->trip->name,
                    'type' => $this->trip->type,
                    'status' => $this->trip->status,
                ];
            }),
        ];
    }
}
