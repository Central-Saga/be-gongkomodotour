<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripPrices extends Model
{
    /** @use HasFactory<\Database\Factories\TripPricesFactory> */
    use HasFactory;

    protected $table = 'trip_prices';

    protected $fillable = [
        'trip_duration_id',
        'pax_min',
        'pax_max',
        'price_per_pax',
        'status',
    ];

    public function tripDuration()
    {
        return $this->belongsTo(TripDuration::class);
    }
}
