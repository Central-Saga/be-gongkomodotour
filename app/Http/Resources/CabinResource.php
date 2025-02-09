<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CabinResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'boat_id'          => $this->boat_id,
            'cabin_name'       => $this->cabin_name,
            'bed_type'         => $this->bed_type,
            'min_pax'          => $this->min_pax,
            'max_pax'          => $this->max_pax,
            'base_price'       => $this->base_price,
            'additional_price' => $this->additional_price,
            'status'           => $this->status,
            'created_at'       => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at'       => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
        ];
    }
}