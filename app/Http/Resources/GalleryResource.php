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
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];

        // Jika assets sudah di-load, tambahkan ke response
        if ($this->relationLoaded('assets')) {
            $data['assets'] = $this->assets->map(function ($asset) {
                return [
                    'id' => $asset->id,
                    'title' => $asset->title,
                    'description' => $asset->description,
                    'file_path' => $asset->file_path,
                    'file_url' => $asset->file_url,
                    'is_external' => $asset->is_external,
                    'created_at' => $asset->created_at->toDateTimeString(),
                    'updated_at' => $asset->updated_at->toDateTimeString(),
                ];
            });
        }

        return $data;
    }
}
