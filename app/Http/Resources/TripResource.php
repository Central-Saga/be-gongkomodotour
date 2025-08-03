<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CabinResource;

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
            'boat_ids' => $this->boats->pluck('id')->toArray(),
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
            'has_hotel' => (bool) $this->has_hotel,
            'operational_days' => $this->operational_days,
            'tentation' => $this->tentation,
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,

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

            'assets' => $this->whenLoaded('assets', function () {
                return AssetResource::collection($this->assets);
            }),

            'boats' => $this->whenLoaded('boats', function () {
                return BoatResource::collection($this->boats);
            }),

            'cabin' => $this->when($this->boats && $this->boats->isNotEmpty() && $this->boats->first()->relationLoaded('cabin'), function () {
                return CabinResource::collection($this->boats->flatMap->cabin);
            }),

            'testimonials' => $this->whenLoaded('testimonials', function () {
                return TestimonialResource::collection($this->testimonials);
            }),
        ];
    }
}
