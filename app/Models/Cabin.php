<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabin extends Model
{
    use HasFactory;

    protected $table = 'cabin';
    protected $fillable = ['boat_id', 'cabin_name', 'bed_type', 'bathroom', 'min_pax', 'max_pax', 'base_price', 'additional_price', 'status'];

    public function boat()
    {
        return $this->belongsTo(Boat::class, 'boat_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }
}
