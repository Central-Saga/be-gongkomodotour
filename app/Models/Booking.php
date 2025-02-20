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
        'trip_duration_id',
        'customer_id',
        'user_id',
        'hotel_occupancy_id',
        'total_price',
        'total_pax',
        'status',
        'start_date',
        'end_date',
    ];

    protected $appends = ['computed_cabin_price', 'computed_total_price'];

    public function trip()
    {
        return $this->belongsTo(Trips::class);
    }

    public function tripDuration()
    {
        return $this->belongsTo(TripDuration::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customers::class);
    }

    public function boat()
    {
        return $this->belongsToMany(Boat::class, 'booking_boat')
            ->withTimestamps();
    }

    public function cabin()
    {
        return $this->belongsToMany(Cabin::class, 'booking_cabin')
            ->withPivot('total_pax', 'total_price')
            ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hotelOccupancy()
    {
        return $this->belongsTo(HotelOccupancies::class);
    }

    public function additionalFees()
    {
        return $this->belongsToMany(AdditionalFee::class, 'booking_fees')
            ->withPivot('total_price')
            ->withTimestamps();
    }

    // Akses harga cabin berdasarkan jumlah penumpang yang dipesan
    public function getComputedCabinPriceAttribute()
    {
        return $this->cabin->sum(function ($cabin) {
            // Misalnya, jika data jumlah pax untuk cabin tersimpan pada pivot,
            // kalau tidak, fallback menggunakan $this->total_pax
            $pivotTotalPax = $cabin->pivot->total_pax ?? $this->total_pax;
            $basePrice = $cabin->base_price;
            $additionalPrice = $cabin->additional_price;
            $minPax = $cabin->min_pax;
            $maxPax = $cabin->max_pax;

            if ($pivotTotalPax > $maxPax) {
                $pivotTotalPax = $maxPax;
            }
            if ($pivotTotalPax <= $minPax) {
                return $basePrice;
            }
            $extraPax = $pivotTotalPax - $minPax;
            return $basePrice + ($extraPax * $additionalPrice);
        });
    }

    /**
     * Menghitung total harga booking yang mencakup harga cabin, hotel, trip, dan booking fee.
     *
     * Properti ini tidak tersimpan di database, melainkan dihitung secara dinamis.
     *
     * @return float
     */
    public function getComputedTotalPriceAttribute()
    {
        $totalPax = $this->total_pax;

        // Mengambil harga cabin dari accessor getComputedCabinPriceAttribute()
        $cabinPrice = $this->computed_cabin_price;

        // Mengambil harga hotel jika tersedia
        $hotelPrice = $this->hotelOccupancy ? $this->hotelOccupancy->price : 0;

        // Mengambil harga trip jika tersedia
        $tripPrice = 0;
        if ($this->tripDuration && isset($this->tripDuration->trip_price)) {
            $tripPrice = $this->tripDuration->trip_price;
        }

        // Total biaya dasar (asumsi harga yang dihitung adalah per orang)
        $baseTotal = ($cabinPrice + $hotelPrice + $tripPrice) * $totalPax;

        // Menghitung total fee tambahan dengan mengakses nilai total_price dari pivot pada relasi additionalFees
        $bookingFeeTotal = $this->additionalFees->sum(function ($fee) {
            return $fee->pivot->total_price;
        });

        return $baseTotal + $bookingFeeTotal;
    }
}
