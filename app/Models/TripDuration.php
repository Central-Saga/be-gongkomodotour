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
        'status',
    ];

    public function trip()
    {
        return $this->belongsTo(Trips::class);
    }
}
