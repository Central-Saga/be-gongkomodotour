<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GalleryResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'assets' => $this->whenLoaded('assets', function () {
                return $this->assets->map(function ($asset) {
                    return [
                        'id' => $asset->id,
                        'title' => $asset->title,
                        'description' => $asset->description,
                        'file_path' => $asset->file_path,
                        'file_url' => $asset->file_url,
                    ];
                });
            }),
        ];
    }
}
