<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdditionalFeeResource extends JsonResource
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
            'fee_category' => $this->fee_category,
            'price' => $this->price,
            'region' => $this->region,
            'unit' => $this->unit,
            'pax_min' => $this->pax_min,
            'pax_max' => $this->pax_max,
            'day_type' => $this->day_type,
            'is_required' => $this->is_required,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
