<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory;

    protected $table = 'bookings';

    protected $fillable = [
        'trip_id',
        'customer_id',
        'boat_id',
        'cabin_id',
        'user_id',
        'hotel_occupancy_id',
        'total_price',
        'total_pax',
        'status',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function boat()
    {
        return $this->belongsTo(Boat::class);
    }

    public function cabin()
    {
        return $this->belongsTo(Cabin::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hotelOccupancy()
    {
        return $this->belongsTo(HotelOccupancy::class);
    }
}
