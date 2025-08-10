<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'author_id'  => $this->author_id,
            'title'      => $this->title,
            'category'   => $this->category,
            'slug'       => $this->slug,
            'content'    => $this->content,
            'status'     => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'author'     => new UserResource($this->whenLoaded('author')),
            'assets'     => AssetResource::collection($this->whenLoaded('assets')),
        ];
    }
}
