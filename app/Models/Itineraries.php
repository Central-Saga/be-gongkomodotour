<?php

namespace App\Models;

use App\Models\Trips;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Itineraries extends Model
{
    /** @use HasFactory<\Database\Factories\ItinerariesFactory> */
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'day_number',
        'activities',
    ];

    public function trip()
    {
        return $this->belongsTo(Trips::class);
    }
}
