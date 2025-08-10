<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarouselResource extends JsonResource
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
            'title'       => $this->title,
            'description' => $this->description,
            'link'        => $this->link,
            'order_num'   => $this->order_num,
            'is_active'   => $this->is_active,
            'assets'      => $this->whenLoaded('assets', function () {
                return AssetResource::collection($this->assets);
            }),
            'created_at'  => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at'  => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
        ];
    }
}
