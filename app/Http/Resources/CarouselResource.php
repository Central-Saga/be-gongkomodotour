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
            'order_num'   => $this->order_num,
            'is_active'   => $this->is_active,
            'assets'      => AssetResource::collection($this->whenLoaded('assets', $this->assets)),
            'primary_image' => $this->when($this->primary_image, function () {
                return new AssetResource($this->primary_image);
            }),
            'created_at'  => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at'  => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
        ];
    }
}
