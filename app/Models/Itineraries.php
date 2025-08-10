<?php

namespace App\Models;

use App\Models\TripDuration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Itineraries extends Model
{
    /** @use HasFactory<\Database\Factories\ItinerariesFactory> */
    use HasFactory;

    protected $fillable = [
        'trip_duration_id',
        'day_number',
        'activities',
    ];

    public function tripDuration()
    {
        return $this->belongsTo(TripDuration::class, 'trip_duration_id', 'id');
    }
}
