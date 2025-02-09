<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlightScheduleResource extends JsonResource
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
            'trip_id' => $this->trip_id,
            'route' => $this->route,
            'eta_time' => $this->eta_time,
            'eta_text' => $this->eta_text,
            'etd_time' => $this->etd_time,
            'etd_text' => $this->etd_text,
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
