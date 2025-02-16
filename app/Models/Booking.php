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

    public function tripDuration()
    {
        return $this->belongsTo(TripDuration::class);
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

    public function bookingFees()
    {
        return $this->hasMany(BookingFee::class);
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
        $cabinPrice = $this->computed_cabin_price; // Akses sebagai properti

        // Mengambil harga hotel, jika tersedia
        $hotelPrice = $this->hotelOccupancy ? $this->hotelOccupancy->price : 0;

        // Mengambil harga trip.
        // Asumsikan kamu memiliki logika atau relasi untuk mendapatkan harga trip.
        // Pada contoh berikut, kita gunakan nilai default 0 atau bisa kamu sesuaikan.
        $tripPrice = 0;
        if ($this->tripDuration && isset($this->tripDuration->trip_price)) {
            $tripPrice = $this->tripDuration->trip_price;
        }

        // Total biaya dasar berdasarkan harga-harga di atas
        $baseTotal = ($cabinPrice + $hotelPrice + $tripPrice) * $totalPax;

        // Menghitung total booking fee yang tersimpan melalui relasi bookingFees.
        $bookingFeeTotal = $this->bookingFees->sum('total_price');

        return $baseTotal + $bookingFeeTotal;
    }
}
