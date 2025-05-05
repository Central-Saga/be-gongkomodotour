<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'trip_id' => $this->trip_id,
            'trip_duration_id' => $this->trip_duration_id,
            'user_id' => $this->user_id,
            'hotel_occupancy_id' => $this->hotel_occupancy_id,
            'total_price' => $this->total_price,
            'total_pax' => $this->total_pax,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
            'customer_address' => $this->customer_address,
            'customer_country' => $this->customer_country,
            'customer_phone' => $this->customer_phone,

            'trip' => $this->whenLoaded('trip', function () {
                return TripResource::make($this->trip);
            }),

            'trip_duration' => $this->whenLoaded('tripDuration', function () {
                return TripDurationResource::make($this->tripDuration);
            }),

            'boat' => $this->whenLoaded('boat', function () {
                return BoatResource::collection($this->boat);
            }),

            'cabin' => $this->whenLoaded('cabin', function () {
                return CabinResource::collection($this->cabin);
            }),

            'user' => $this->whenLoaded('user', function () {
                return UserResource::make($this->user);
            }),

            'hotel_occupancy' => $this->whenLoaded('hotelOccupancy', function () {
                return HotelOccupancyResource::make($this->hotelOccupancy);
            }),

            'additional_fees' => $this->whenLoaded('additionalFees', function () {
                return AdditionalFeeResource::collection($this->additionalFees);
            }),
        ];
    }
}
