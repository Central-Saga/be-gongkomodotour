<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'boat_name'         => $this->boat_name,
            'spesification'     => $this->spesification,
            'cabin_information' => $this->cabin_information,
            'facilities'        => $this->facilities,
            'status'            => $this->status,
            'created_at'        => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at'        => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
        ];
    }
}