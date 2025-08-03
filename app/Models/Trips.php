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
        'has_hotel',
        'operational_days',
        'tentation',
    ];

    protected $casts = [
        'has_boat' => 'boolean',
        'has_hotel' => 'boolean',
        'operational_days' => 'array',
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

    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class, 'trip_id', 'id');
    }

    public function boats()
    {
        return $this->belongsToMany(Boat::class, 'trip_boat', 'trip_id', 'boat_id');
    }

    /**
     * Check if trip operates on specific day
     *
     * @param string $day
     * @return bool
     */
    public function operatesOnDay($day)
    {
        if (!$this->operational_days) {
            return true; // If no operational days set, assume operates every day
        }

        return in_array($day, $this->operational_days);
    }

    /**
     * Get available operational days
     *
     * @return array
     */
    public static function getAvailableDays()
    {
        return [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];
    }

    /**
     * Check if trip is tentation
     *
     * @return bool
     */
    public function isTentation()
    {
        return $this->tentation === 'Yes';
    }
}
