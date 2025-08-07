<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\FileUrlService;

class AssetResource extends JsonResource
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
            'file_url' => FileUrlService::generateAssetUrl($this->resource),
            'original_file_url' => $this->file_url, // Untuk debugging
            'is_external' => (bool) $this->is_external,
            'file_path' => $this->file_path, // Untuk debugging
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
