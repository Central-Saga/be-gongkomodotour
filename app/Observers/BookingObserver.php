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
        // Hanya set end_date
        $this->setEndDate($booking);
    }

    public function created(Booking $booking)
    {
        // Refresh untuk memastikan semua relasi tersinkronisasi
        $booking->refresh();
        $this->calculateTotalPrice($booking);
    }

    public function updating(Booking $booking)
    {
        // Hanya set end_date
        $this->setEndDate($booking);
    }

    public function updated(Booking $booking)
    {
        // Refresh untuk memastikan semua relasi tersinkronisasi
        $booking->refresh();
        $this->calculateTotalPrice($booking);
    }

    private function setEndDate(Booking $booking)
    {
        if ($booking->start_date && $booking->tripDuration && $booking->tripDuration->duration_days) {
            $duration = $booking->tripDuration->duration_days;
            $booking->end_date = Carbon::parse($booking->start_date)
                ->addDays($duration - 1)
                ->format('Y-m-d');
        }
    }

    private function calculateTotalPrice(Booking $booking)
    {
        $totalPax = $booking->total_pax;

        // Hitung harga cabin
        $cabinPrice = $booking->computed_cabin_price;

        // Hitung harga hotel
        $hotelPrice = $this->getHotelPrice($booking);

        // Hitung harga trip
        $tripPrice = $this->getTripPrice($booking, $totalPax);

        // Hitung total dasar
        $baseTotal = ($cabinPrice + $hotelPrice + $tripPrice) * $totalPax;

        // Hitung total booking fee
        $totalBookingFee = $booking->additionalFees->sum(function ($fee) {
            return $fee->pivot->total_price;
        });

        // Update total price
        $booking->total_price = $baseTotal + $totalBookingFee;
        $booking->saveQuietly();
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
