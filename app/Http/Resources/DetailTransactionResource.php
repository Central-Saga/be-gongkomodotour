<?php

namespace App\Http\Resources;

use App\Http\Resources\HotelRequestResource;
use App\Http\Resources\SurchargeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\HotelRequest;
use App\Models\Surcharge;

class DetailTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $referenceData = null;

        if ($this->reference_type === HotelRequest::class) {
            $referenceData = new HotelRequestResource($this->whenLoaded('reference'));
        } elseif ($this->reference_type === Surcharge::class) {
            $referenceData = new SurchargeResource($this->whenLoaded('reference'));
        }

        return [
            'id' => $this->id,
            'transaction_id' => $this->transaction_id,
            'type' => $this->type,
            'amount' => $this->amount,
            'description' => $this->description,
            'reference' => $referenceData,
        ];
    }
}
