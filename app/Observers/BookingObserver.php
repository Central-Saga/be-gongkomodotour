<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\Cabin;
use App\Models\HotelOccupancies;
use App\Models\TripDuration;

class BookingObserver
{
    public function creating(Booking $booking)
    {
        $cabin = Cabin::find($booking->cabin_id);
        $totalPax = $booking->total_pax;

        if ($cabin) {
            $basePrice = $cabin->base_price;
            $additionalPrice = $cabin->additional_price ?? 0;
            $minPax = $cabin->min_pax;

            // Pastikan total pax tidak melebihi kapasitas maksimum
            if ($totalPax > $cabin->max_pax) {
                $totalPax = $cabin->max_pax;
            }

            if ($totalPax <= $minPax) {
                $cabinPrice = $basePrice;
            } else {
                $extraPax = $totalPax - $minPax;
                $cabinPrice = $basePrice + ($extraPax * $additionalPrice);
            }
        } else {
            $cabinPrice = 0;
        }

        // Mendapatkan harga hotel occupancy jika ada
        $hotelPrice = 0;
        if ($booking->hotel_occupancy_id) {
            $hotel = HotelOccupancies::find($booking->hotel_occupancy_id);
            if ($hotel) {
                $hotelPrice = $hotel->price;
            }
        }

        // Jika user memilih trip duration, gunakan pilihan tersebut untuk perhitungan harga trip
        $tripPrice = 0;
        if ($booking->trip_duration_id) {
            $tripDuration = TripDuration::with('tripPrices')->find($booking->trip_duration_id);
            if ($tripDuration && $tripDuration->tripPrices) {
                foreach ($tripDuration->tripPrices as $price) {
                    if (
                        $totalPax >= $price->pax_min &&
                        $totalPax <= $price->pax_max &&
                        $price->status === 'Aktif'
                    ) {
                        $tripPrice = $price->price_per_pax;
                        break;
                    }
                }
            }
        } else {
            $tripDuration = TripDuration::where('status', 'Aktif')->first();
            if ($tripDuration && $tripDuration->tripPrices->isNotEmpty()) {
                $tripPrice = $tripDuration->tripPrices->first()->price_per_pax;
            }
        }

        // Hitung total_price dengan menggabungkan harga cabin, hotel, dan trip
        $booking->total_price = ($cabinPrice + $hotelPrice + $tripPrice) * $totalPax;
    }
}
