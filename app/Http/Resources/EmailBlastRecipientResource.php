<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailBlastRecipientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'email_blast_id'   => $this->email_blast_id,
            'recipient_email'  => $this->recipient_email,
            'status'           => $this->status,
            'email_blast'             => new EmailBlastResource($this->whenLoaded('boat')),
            'created_at'       => $this->created_at ?$this->created_at->toDateTimeString() : null,
            'updated_at'       => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
        ];
    }
}