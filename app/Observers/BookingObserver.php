<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\Cabin;
use App\Models\HotelOccupancies;

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

        // Hitung total_price: (harga cabin + harga hotel) dikalikan dengan total pax
        $booking->total_price = ($cabinPrice + $hotelPrice) * $totalPax;
    }
}
