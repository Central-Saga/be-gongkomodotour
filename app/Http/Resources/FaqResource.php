<?php
// filepath: /c:/laragon/www/be-gongkomodotour/app/Http/Resources/FaqResource.php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
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
            'id'            => $this->id,
            'question'      => $this->question,
            'answer'        => $this->answer,
            'category'      => $this->category,
            'display_order' => $this->display_order,
            'status'        => $this->status,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}