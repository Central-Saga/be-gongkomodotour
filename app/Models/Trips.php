<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trips extends Model
{
    /** @use HasFactory<\Database\Factories\TripsFactory> */
    use HasFactory;

    protected $table = 'trips';

    protected $fillable = [
        'name',
        'include',
        'exclude',
        'note',
        'start_time',
        'end_time',
        'meeting_point',
        'type',
        'status',
        'is_highlight',
        'destination_count',
        'has_boat',
    ];

    public function flightSchedule()
    {
        return $this->hasMany(FlightSchedule::class, 'trip_id', 'id');
    }

    public function tripDuration()
    {
        return $this->hasMany(TripDuration::class, 'trip_id', 'id');
    }

    public function additionalFees()
    {
        return $this->hasMany(AdditionalFee::class, 'trip_id', 'id');
    }

    public function surcharges()
    {
        return $this->hasMany(Surcharge::class, 'trip_id', 'id');
    }

    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class, 'trip_id', 'id');
    }
}
