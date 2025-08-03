<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boat extends Model
{
    use HasFactory;

    protected $table = 'boat';
    protected $fillable = ['boat_name', 'spesification', 'cabin_information', 'facilities', 'status'];

    public function cabin()
    {
        return $this->hasMany(Cabin::class, 'boat_id');
    }

    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }

    public function trips()
    {
        return $this->belongsToMany(Trips::class, 'trip_boat', 'boat_id', 'trip_id');
    }
}
