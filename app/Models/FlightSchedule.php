<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightSchedule extends Model
{
    /** @use HasFactory<\Database\Factories\FlightScheduleFactory> */
    use HasFactory;

    protected $table = 'flight_schedules';

    protected $fillable = [
        'trip_id',
        'route',
        'eta_time',
        'eta_text',
        'etd_time',
        'etd_text',
    ];

    public function trip()
    {
        return $this->belongsTo(Trips::class, 'trip_id', 'id');
    }
}
