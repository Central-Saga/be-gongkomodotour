<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cabin;
use App\Models\HotelOccupancy;

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

    // Akses harga cabin berdasarkan jumlah penumpang yang dipesan
    public function getComputedCabinPriceAttribute()
    {
        $cabin = $this->cabin;
        if (!$cabin) {
            return 0;
        }
        $totalPax = $this->total_pax;
        $basePrice = $cabin->base_price;
        $additionalPrice = $cabin->additional_price;
        $minPax = $cabin->min_pax;

        // Validasi agar total pax tidak melebihi max pax
        if ($totalPax > $cabin->max_pax) {
            // Misalnya, Anda dapat memunculkan exception atau mengatur nilai default
            // throw new \Exception("Jumlah pax melebihi kapasitas maksimum.");
            $totalPax = $cabin->max_pax;
        }

        if ($totalPax <= $minPax) {
            return $basePrice;
        }

        $extraPax = $totalPax - $minPax;
        return $basePrice + ($extraPax * $additionalPrice);
    }
}
