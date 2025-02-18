<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\BookingResource;
use App\Services\Contracts\BookingServiceInterface;
use App\Http\Requests\BookingStoreRequest;
use App\Http\Requests\BookingUpdateRequest;

class BookingController extends Controller implements HasMiddleware
{
    protected $bookingService;

    public static function middleware()
    {
        return [
            'permission:mengelola bookings',
        ];
    }

    public function __construct(BookingServiceInterface $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index(Request $request)
    {
        $status = $request->query('status');
        if ($status) {
            if (strtolower($status) == '0') {
                $bookings = $this->bookingService->getBookingByStatus('Pending');
            } elseif (strtolower($status) == '1') {
                $bookings = $this->bookingService->getBookingByStatus('Confirmed');
            } elseif (strtolower($status) == '2') {
                $bookings = $this->bookingService->getBookingByStatus('Cancelled');
            } else {
                return response()->json(['message' => 'Invalid status parameter'], 400);
            }
        } else {
            $bookings = $this->bookingService->getAllBookings();
        }
        return BookingResource::collection($bookings);
    }

    public function store(BookingStoreRequest $request)
    {
        $booking = $this->bookingService->createBooking($request->all());
        if (!$booking) {
            return response()->json(['message' => 'Failed to create booking'], 400);
        }
        return new BookingResource($booking);
    }

    public function update(BookingUpdateRequest $request, $id)
    {
        $booking = $this->bookingService->updateBooking($id, $request->all());
        if (!$booking) {
            return response()->json(['message' => 'Failed to update booking'], 400);
        }
        return new BookingResource($booking);
    }

    public function destroy($id)
    {
        $result = $this->bookingService->deleteBooking($id);
        if (!$result) {
            return response()->json(['message' => 'Failed to delete booking'], 400);
        }
        return response()->json(['message' => 'Booking deleted successfully']);
    }
}
