<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surcharge extends Model
{
    /** @use HasFactory<\Database\Factories\SurchargeFactory> */
    use HasFactory;

    protected $table = 'surcharges';

    protected $fillable = [
        'hotel_occupancy_id',
        'season',
        'start_date',
        'end_date',
        'surcharge_price',
        'status',
    ];

    public function hotelOccupancy()
    {
        return $this->belongsTo(HotelOccupancies::class, 'hotel_occupancy_id', 'id');
    }

    public function detailTransactions()
    {
        return $this->morphMany(DetailTransaction::class, 'reference');
    }
}
