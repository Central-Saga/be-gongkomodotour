<?php

namespace App\Observers;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\HotelOccupancies;
use App\Models\TripDuration;

class BookingObserver
{
    public function creating(Booking $booking)
    {
        $totalPax = $booking->total_pax;

        // Menggunakan computed attribute dari model Booking yang sudah menangani relasi many-to-many cabin
        $cabinPrice = $booking->computed_cabin_price;

        $hotelPrice = $this->getHotelPrice($booking);
        $tripPrice  = $this->getTripPrice($booking, $totalPax);

        // Asumsikan computed_cabin_price sudah menjumlahkan harga untuk seluruh cabin yang dipesan
        $baseTotal = $cabinPrice + $hotelPrice + $tripPrice;

        // Menghitung total fee tambahan memakai relasi additionalFees dan nilai dari pivot
        $totalBookingFee = $booking->additionalFees->sum(function ($fee) {
            return $fee->pivot->total_price;
        });

        $booking->total_price = $baseTotal + $totalBookingFee;

        // Hitung end_date berdasarkan start_date dan trip duration
        if ($booking->start_date && $booking->tripDuration && $booking->tripDuration->duration_days) {
            $duration = $booking->tripDuration->duration_days;
            $booking->end_date = Carbon::parse($booking->start_date)
                ->addDays($duration - 1)
                ->format('Y-m-d');
        }
    }

    public function updating(Booking $booking)
    {
        $totalPax = $booking->total_pax;

        // Menggunakan computed attribute untuk menghitung harga cabin dari relasi many-to-many
        $cabinPrice = $booking->computed_cabin_price;

        $hotelPrice = $this->getHotelPrice($booking);
        $tripPrice  = $this->getTripPrice($booking, $totalPax);

        $baseTotal = $cabinPrice + $hotelPrice + $tripPrice;

        $totalBookingFee = $booking->additionalFees->sum(function ($fee) {
            return $fee->pivot->total_price;
        });

        $booking->total_price = $baseTotal + $totalBookingFee;

        if ($booking->start_date && $booking->tripDuration && $booking->tripDuration->duration_days) {
            $duration = $booking->tripDuration->duration_days;
            $booking->end_date = Carbon::parse($booking->start_date)
                ->addDays($duration - 1)
                ->format('Y-m-d');
        }
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
