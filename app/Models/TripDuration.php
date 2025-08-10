<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripDuration extends Model
{
    /** @use HasFactory<\Database\Factories\TripDurationFactory> */
    use HasFactory;

    protected $table = 'trip_durations';

    protected $fillable = [
        'trip_id',
        'duration_label',
        'duration_days',
        'duration_nights',
        'status',
    ];

    public function trip()
    {
        return $this->belongsTo(Trips::class);
    }

    public function tripPrices()
    {
        return $this->hasMany(TripPrices::class, 'trip_duration_id', 'id');
    }

    public function itineraries()
    {
        return $this->hasMany(Itineraries::class, 'trip_duration_id', 'id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
