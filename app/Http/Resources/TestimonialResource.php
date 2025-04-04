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
            'id'           => $this->id,
            'customer_id'  => $this->customer_id,
            'rating'       => $this->rating,
            'review'       => $this->review,
            'is_approved'  => $this->is_approved,
            'is_highlight' => $this->is_highlight,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,

            'customer' => $this->whenLoaded('customer', function () {
                return new CustomerResource($this->customer);
            }),

            'user' => $this->whenLoaded('customer.user', function () {
                return new UserResource($this->customer->user);
            }),
        ];
    }
}
