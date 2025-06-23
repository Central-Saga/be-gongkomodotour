<?php
// filepath: /c:/laragon/www/be-gongkomodotour/app/Http/Resources/TestimonialResource.php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestimonialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'customer_name'    => $this->customer_name,
            'customer_email'   => $this->customer_email,
            'customer_phone'   => $this->customer_phone,
            'trip_id'          => $this->trip_id,
            'rating'           => $this->rating,
            'review'           => $this->review,
            'is_approved'      => $this->is_approved,
            'is_highlight'     => $this->is_highlight,
            'source'           => $this->source,
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,

            'trip' => $this->whenLoaded('trip', function () {
                return new TripResource($this->trip);
            }),
        ];
    }
}
