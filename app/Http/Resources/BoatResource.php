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
            'cabin'             => $this->whenLoaded('cabin', function () {
                return CabinResource::collection($this->cabin);
            }),
            'assets'            => $this->whenLoaded('assets', function () {
                return AssetResource::collection($this->assets);
            }),
            'assets_cabin'      => $this->whenLoaded('cabin.assets', function () {
                return AssetResource::collection($this->cabin->assets);
            }),
            'trips'             => $this->whenLoaded('trips', function () {
                return TripResource::collection($this->trips);
            }),
        ];
    }
}
