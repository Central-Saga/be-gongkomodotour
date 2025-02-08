<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'user_id'     => $this->user_id,
            'alamat'      => $this->alamat,
            'no_hp'       => $this->no_hp,
            'nasionality' => $this->nasionality,
            'region'      => $this->region,
            'status'      => $this->status,
            'created_at'  => $this->created_at->toDateTimeString(),
            'updated_at'  => $this->updated_at->toDateTimeString(),
            'user'        => new UserResource($this->whenLoaded('user')),
        ];
    }
}