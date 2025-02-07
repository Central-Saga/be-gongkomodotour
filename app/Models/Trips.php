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
        'duration',
        'start_time',
        'end_time',
        'meeting_point',
        'type',
        'status',
    ];

    public function itineraries()
    {
        return $this->hasMany(Itineraries::class);
    }
}
