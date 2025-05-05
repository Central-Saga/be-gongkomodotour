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
        'user_id',
        'hotel_occupancy_id',
        'customer_name',
        'customer_email',
        'customer_address',
        'customer_country',
        'customer_phone',
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
        return $this->belongsTo(User::class)->withDefault();
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
        $totalCabinPrice = 0;
        foreach ($this->cabin as $cabin) {
            // Ambil total_pax dari pivot atau fallback ke booking total_pax
            $pivotTotalPax = $cabin->pivot->total_pax ?? $this->total_pax;
            $basePrice = $cabin->base_price;
            $additionalPrice = $cabin->additional_price;
            $minPax = $cabin->min_pax;
            $maxPax = $cabin->max_pax;

            // Logging untuk debugging per hitungan
            \Log::info('Perhitungan Cabin', [
                'cabin_id'         => $cabin->id,
                'pivotTotalPax'    => $pivotTotalPax,
                'base_price'       => $basePrice,
                'additional_price' => $additionalPrice,
                'min_pax'          => $minPax,
                'max_pax'          => $maxPax,
            ]);

            // Sesuaikan total pax jika melebihi max dan hitung harga
            if ($pivotTotalPax > $maxPax) {
                $pivotTotalPax = $maxPax;
            }
            if ($pivotTotalPax <= $minPax) {
                $price = $basePrice;
            } else {
                $extraPax = $pivotTotalPax - $minPax;
                $price = $basePrice + ($extraPax * $additionalPrice);
            }
            $totalCabinPrice += $price;
        }
        return $totalCabinPrice;
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

        // Menghitung harga hotel secara dinamis berdasarkan jumlah malam
        $hotelPrice = 0;
        if ($this->hotelOccupancy && $this->tripDuration) {
            $nights = $this->tripDuration->duration_nights ?? ($this->tripDuration->duration_days - 1);
            $hotelPrice = $this->hotelOccupancy->calculateHotelFee($totalPax, $nights);
        }

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
