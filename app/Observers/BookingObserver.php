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

        $booking->total_price = ($cabinPrice + $hotelPrice + $tripPrice) * $totalPax;
    }

    public function created(Booking $booking)
    {
        $this->createOrUpdateBookingFees($booking);
    }

    public function updated(Booking $booking)
    {
        // Jika ada perubahan yang memengaruhi booking fee,
        // hapus fee lama dan generate ulang yang baru
        $booking->bookingFees()->delete();
        $this->createOrUpdateBookingFees($booking);
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

    private function createOrUpdateBookingFees(Booking $booking)
    {
        if (!$booking->trip_id) {
            return;
        }

        // Buat atau update booking fee untuk fee wajib
        $mandatoryFees = AdditionalFee::where('trip_id', $booking->trip_id)
            ->where('status', 'Aktif')
            ->where('is_required', true)
            ->get();

        foreach ($mandatoryFees as $fee) {
            BookingFee::updateOrCreate(
                [
                    'booking_id' => $booking->id,
                    'additional_fee_id' => $fee->id,
                ],
                [
                    'fee_type'    => $fee->fee_category,
                    'total_price' => $fee->price,
                ]
            );
        }

        // Tangani fee optional jika disediakan melalui properti optional_additional_fees
        if (isset($booking->optional_additional_fees) && is_array($booking->optional_additional_fees)) {
            foreach ($booking->optional_additional_fees as $feeId) {
                $fee = AdditionalFee::find($feeId);
                if ($fee && !$fee->is_required && $fee->status === 'Aktif') {
                    BookingFee::updateOrCreate(
                        [
                            'booking_id' => $booking->id,
                            'additional_fee_id' => $fee->id,
                        ],
                        [
                            'fee_type'    => $fee->fee_category,
                            'total_price' => $fee->price,
                        ]
                    );
                }
            }
        }
    }
}
