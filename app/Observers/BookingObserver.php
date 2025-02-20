<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\Cabin;
use App\Models\HotelOccupancies;
use App\Models\TripDuration;
use App\Models\AdditionalFee;
use App\Models\BookingFee;

class BookingObserver
{
    public function creating(Booking $booking)
    {
        $totalPax = $booking->total_pax;
        $cabinPrice = $this->getCabinPrice($booking, $totalPax);
        $hotelPrice = $this->getHotelPrice($booking);
        $tripPrice  = $this->getTripPrice($booking, $totalPax);

        // Hitung biaya dasar untuk tiap pax
        $baseTotal = ($cabinPrice + $hotelPrice + $tripPrice) * $totalPax;

        // Mengambil total booking fee jika sudah ada (misalnya melalui relasi bookingFees)
        $totalBookingFee = $booking->bookingFees->sum('total_price');

        $booking->total_price = $baseTotal + $totalBookingFee;

        // Hitung end_date berdasarkan start_date yang dipilih dan trip duration
        // Misalnya, trip duration menyimpan property 'duration' (jumlah hari)
        if ($booking->start_date && $booking->tripDuration && $booking->tripDuration->duration_days) {
            $duration = $booking->tripDuration->duration_days;
            // Misalnya, end_date = start_date + (duration - 1) hari
            $booking->end_date = Carbon::parse($booking->start_date)
                ->addDays($duration - 1)
                ->format('Y-m-d');
        }
    }

    public function updating(Booking $booking)
    {
        $totalPax = $booking->total_pax;
        $cabinPrice = $this->getCabinPrice($booking, $totalPax);
        $hotelPrice = $this->getHotelPrice($booking);
        $tripPrice  = $this->getTripPrice($booking, $totalPax);

        $baseTotal = ($cabinPrice + $hotelPrice + $tripPrice) * $totalPax;

        $totalBookingFee = $booking->bookingFees->sum('total_price');

        $booking->total_price = $baseTotal + $totalBookingFee;

        // Hitung ulang end_date jika start_date atau tripDuration berubah
        if ($booking->start_date && $booking->tripDuration && $booking->tripDuration->duration_days) {
            $duration = $booking->tripDuration->duration_days;
            $booking->end_date = Carbon::parse($booking->start_date)
                ->addDays($duration - 1)
                ->format('Y-m-d');
        }
    }

    private function getCabinPrice(Booking $booking, $totalPax)
    {
        $cabin = Cabin::find($booking->cabin_id);
        if (!$cabin) {
            return 0;
        }
        $basePrice       = $cabin->base_price;
        $additionalPrice = $cabin->additional_price ?? 0;
        $minPax          = $cabin->min_pax;

        if ($totalPax > $cabin->max_pax) {
            $totalPax = $cabin->max_pax;
        }

        if ($totalPax <= $minPax) {
            return $basePrice;
        }

        $extraPax = $totalPax - $minPax;
        return $basePrice + ($extraPax * $additionalPrice);
    }

    private function getHotelPrice(Booking $booking)
    {
        $hotelPrice = 0;
        if ($booking->hotel_occupancy_id) {
            $hotel = HotelOccupancies::find($booking->hotel_occupancy_id);
            if ($hotel) {
                $hotelPrice = $hotel->price;
            }
        }
        return $hotelPrice;
    }

    private function getTripPrice(Booking $booking, $totalPax)
    {
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
        return $tripPrice;
    }
}
