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
            'duration' => $this->duration,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'meeting_point' => $this->meeting_point,
            'type' => $this->type,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),

            // Relasi
            'itineraries' => $this->whenLoaded('itineraries', function () {
                return ItinerariesResource::collection($this->itineraries);
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
                return SurchargeResource::collection($this->surcharges);
            }),

            'assets' => $this->whenLoaded('assets', function () {
                return AssetResource::collection($this->assets);
            }),
        ];
    }
}
