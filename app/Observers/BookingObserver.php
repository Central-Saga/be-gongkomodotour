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

    public function updating(Booking $booking)
    {
        // Set end_date berdasarkan tripDuration
        $this->setEndDate($booking);
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
}
