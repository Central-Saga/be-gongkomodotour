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
            'customer_id' => $this->customer_id,
            'boat_id' => $this->boat_id,
            'cabin_id' => $this->cabin_id,
            'user_id' => $this->user_id,
            'hotel_occupancy_id' => $this->hotel_occupancy_id,
            'total_price' => $this->total_price,
            'total_pax' => $this->total_pax,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),

            'trip' => $this->whenLoaded('trip', function () {
                return TripResource::make($this->trip);
            }),

            'trip_duration' => $this->whenLoaded('tripDuration', function () {
                return TripDurationResource::make($this->tripDuration);
            }),

            'customer' => $this->whenLoaded('customer', function () {
                return CustomerResource::make($this->customer);
            }),

            'boat' => $this->whenLoaded('boat', function () {
                return BoatResource::make($this->boat);
            }),

            'cabin' => $this->whenLoaded('cabin', function () {
                return CabinResource::make($this->cabin);
            }),

            'user' => $this->whenLoaded('user', function () {
                return UserResource::make($this->user);
            }),

            'hotel_occupancy' => $this->whenLoaded('hotelOccupancy', function () {
                return HotelOccupancyResource::make($this->hotelOccupancy);
            }),

            'booking_fees' => $this->whenLoaded('bookingFees', function () {
                return BookingFeeResource::collection($this->bookingFees);
            }),
        ];
    }
}
