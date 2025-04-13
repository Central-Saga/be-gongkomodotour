<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
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
            'name' => $this->name,
            'include' => $this->include,
            'exclude' => $this->exclude,
            'note' => $this->note,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'meeting_point' => $this->meeting_point,
            'type' => $this->type,
            'status' => $this->status,
            'is_highlight' => $this->is_highlight,
            'destination_count' => $this->destination_count,
            'has_boat' => (bool) $this->has_boat,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),

            // Relasi
            'itineraries' => $this->whenLoaded('tripDuration.itineraries', function () {
                return ItinerariesResource::collection($this->tripDuration->itineraries);
            }),

            'flight_schedules' => $this->whenLoaded('flightSchedule', function () {
                return FlightScheduleResource::collection($this->flightSchedule);
            }),

            'trip_durations' => $this->whenLoaded('tripDuration', function () {
                return TripDurationResource::collection($this->tripDuration);
            }),

            'trip_prices' => $this->whenLoaded('tripDuration.tripPrices', function () {
                return TripPricesResource::collection($this->tripDuration->tripPrices);
            }),

            'additional_fees' => $this->whenLoaded('additionalFees', function () {
                return AdditionalFeeResource::collection($this->additionalFees);
            }),

            'surcharges' => $this->whenLoaded('surcharges', function () {
                return SurchargeResource::collection($this->surcharges->map(function ($surcharge) {
                    $surcharge->surcharge_price = (float) $surcharge->surcharge_price;
                    return $surcharge;
                }));
            }),

            'assets' => $this->whenLoaded('assets', function () {
                return AssetResource::collection($this->assets);
            }),
        ];
    }
}
