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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Relasi
            'itineraries' => $this->whenLoaded('itineraries', function() {
                return $this->itineraries->map(function($itinerary) {
                    return [
                        'id' => $itinerary->id,
                        'day_number' => $itinerary->day_number,
                        'activities' => $itinerary->activities,
                    ];
                });
            }),
            
            'flight_schedules' => $this->whenLoaded('flightSchedule', function() {
                return $this->flightSchedule->map(function($schedule) {
                    return [
                        'id' => $schedule->id,
                        'route' => $schedule->route,
                        'eta_time' => $schedule->eta_time,
                        'eta_text' => $schedule->eta_text,
                        'etd_time' => $schedule->etd_time,
                        'etd_text' => $schedule->etd_text,
                    ];
                });
            }),
            
            'trip_durations' => $this->whenLoaded('tripDuration', function() {
                return $this->tripDuration->map(function($duration) {
                    return [
                        'id' => $duration->id,
                        'duration_label' => $duration->duration_label,
                        'duration_days' => $duration->duration_days,
                        'status' => $duration->status,
                        'prices' => $duration->whenLoaded('prices', function() use ($duration) {
                            return $duration->prices->map(function($price) {
                                return [
                                    'id' => $price->id,
                                    'pax_min' => $price->pax_min,
                                    'pax_max' => $price->pax_max,
                                    'price_per_pax' => $price->price_per_pax,
                                    'status' => $price->status,
                                ];
                            });
                        }),
                    ];
                });
            }),
        ];
    }
}
