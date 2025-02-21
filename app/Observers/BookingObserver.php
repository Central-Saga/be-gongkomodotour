<?php

namespace App\Observers;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\HotelOccupancies;
use App\Models\TripDuration;
use Illuminate\Support\Facades\Log;

class BookingObserver
{
    public function creating(Booking $booking)
    {
        // Set end_date berdasarkan tripDuration
        $this->setEndDate($booking);
    }

    // public function created(Booking $booking)
    // {
    //     // Refresh untuk memastikan semua relasi sudah termuat
    //     $booking->refresh();
    //     $this->calculateTotalPrice($booking);
    // }

    public function updating(Booking $booking)
    {
        // Set end_date berdasarkan tripDuration
        $this->setEndDate($booking);
    }

    // public function updated(Booking $booking)
    // {
    //     // Refresh untuk memastikan relasi terupdate dan hitung ulang total price
    //     $booking->refresh();
    //     $this->calculateTotalPrice($booking);
    // }

    private function setEndDate(Booking $booking)
    {
        if ($booking->start_date && $booking->tripDuration && $booking->tripDuration->duration_days) {
            $duration = $booking->tripDuration->duration_days;
            $booking->end_date = Carbon::parse($booking->start_date)
                ->addDays($duration - 1)
                ->format('Y-m-d');
        }
    }

    // private function calculateTotalPrice(Booking $booking)
    // {
    //     $totalPax = (int) $booking->total_pax;

    //     // Hitung harga cabin: jika relasi cabins ada, gunakan accessor computed_cabin_price; jika tidak, default ke 0.
    //     $cabinPrice = $booking->cabin->count() > 0 ? $booking->computed_cabin_price : 0;

    //     // Hitung harga hotel sesuai dengan hotel occupancy dan durasi menginap
    //     $hotelPrice = $this->getHotelPrice($booking);

    //     // Hitung harga trip berdasarkan tripDuration dan pricing yang terkait
    //     $tripPrice = $this->getTripPrice($booking, $totalPax);

    //     // Titik kustomisasi: jika diperlukan, bisa ditambahkan logika perhitungan atau faktor penyesuaian harga di sini.

    //     // Total dasar: jumlah harga masing-masing komponen dikalikan dengan total penumpang
    //     $baseTotal = ($cabinPrice + $hotelPrice + $tripPrice) * $totalPax;

    //     // Hitung total booking fee dari additional fees
    //     $totalBookingFee = $booking->additionalFees->sum(function ($fee) {
    //         return $fee->pivot->total_price;
    //     });

    //     $finalTotalPrice = $baseTotal + $totalBookingFee;

    //     // Log detail perhitungan untuk memudahkan debug atau penyesuaian lebih lanjut
    //     Log::info('Hasil perhitungan total price booking', [
    //         'total_pax'         => $totalPax,
    //         'cabin_price'       => $cabinPrice,
    //         'hotel_price'       => $hotelPrice,
    //         'trip_price'        => $tripPrice,
    //         'base_total'        => $baseTotal,
    //         'booking_fee_total' => $totalBookingFee,
    //         'final_total_price' => $finalTotalPrice,
    //     ]);

    //     // Update total_price pada booking (tanpa memicu event observer tambahan)
    //     $booking->total_price = $finalTotalPrice;
    //     $booking->saveQuietly();
    // }

    // private function getHotelPrice(Booking $booking)
    // {
    //     $hotelPrice = 0;
    //     if ($booking->hotel_occupancy_id && $booking->tripDuration) {
    //         $hotel = HotelOccupancies::find($booking->hotel_occupancy_id);
    //         if ($hotel) {
    //             // Mengambil jumlah malam yang sesuai: gunakan duration_nights jika ada, atau (duration_days-1) sebagai fallback.
    //             $nights = $booking->tripDuration->duration_nights ?? ($booking->tripDuration->duration_days - 1);
    //             $hotelPrice = $hotel->calculateHotelFee($booking->total_pax, $nights);
    //         }
    //     }
    //     return $hotelPrice;
    // }

    // private function getTripPrice(Booking $booking, $totalPax)
    // {
    //     $tripPrice = 0;
    //     if ($booking->trip_duration_id) {
    //         $tripDuration = TripDuration::with('tripPrices')->find($booking->trip_duration_id);
    //         if ($tripDuration && $tripDuration->tripPrices && count($tripDuration->tripPrices) > 0) {
    //             foreach ($tripDuration->tripPrices as $price) {
    //                 if (
    //                     $totalPax >= $price->pax_min &&
    //                     $totalPax <= $price->pax_max &&
    //                     $price->status === 'Aktif'
    //                 ) {
    //                     $tripPrice = $price->price_per_pax;
    //                     break;
    //                 }
    //             }
    //         }
    //     } else {
    //         // Fallback jika booking tidak memiliki trip_duration_id, gunakan tripDuration aktif jika tersedia
    //         $tripDuration = TripDuration::where('status', 'Aktif')->first();
    //         if ($tripDuration && $tripDuration->tripPrices->isNotEmpty()) {
    //             $tripPrice = $tripDuration->tripPrices->first()->price_per_pax;
    //         }
    //     }
    //     return $tripPrice;
    // }
}
