<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailBlastResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'subject'        => $this->subject,
            'body'           => $this->body,
            'recipient_type' => $this->recipient_type,
            'status'         => $this->status,
            'scheduled_at'   => $this->scheduled_at ? $this->scheduled_at->toDateTimeString() : null,
            'sent_at'        => $this->sent_at ? $this->sent_at->toDateTimeString() : null,
            'created_at'     => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at'     => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
        ];
    }
}
