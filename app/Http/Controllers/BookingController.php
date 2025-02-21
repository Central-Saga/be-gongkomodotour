<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\BookingResource;
use App\Http\Requests\BookingStoreRequest;
use App\Http\Requests\BookingUpdateRequest;
use App\Services\Contracts\BookingServiceInterface;
use Illuminate\Routing\Controllers\HasMiddleware;

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
        if ($request->has('status')) {
            $status = $request->query('status');
            if (strtolower($status) == '0') {
                $bookings = $this->bookingService->getBookingByStatusPending();
            } elseif (strtolower($status) == '1') {
                $bookings = $this->bookingService->getBookingByStatusConfirmed();
            } elseif (strtolower($status) == '2') {
                $bookings = $this->bookingService->getBookingByStatusCancelled();
            } else {
                return response()->json(['message' => 'Invalid status parameter'], 404);
            }
        } else {
            $bookings = $this->bookingService->getAllBookings();
        }
        return BookingResource::collection($bookings);
    }

    public function show($id)
    {
        $booking = $this->bookingService->getBookingById($id);
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }
        return new BookingResource($booking);
    }

    public function store(BookingStoreRequest $request)
    {
        $booking = $this->bookingService->createBooking($request->all());
        if (!$booking) {
            return response()->json(['message' => 'Failed to create booking'], 404);
        }
        return new BookingResource($booking);
    }

    public function update(BookingUpdateRequest $request, $id)
    {
        $booking = $this->bookingService->updateBooking($id, $request->all());
        if (!$booking) {
            return response()->json(['message' => 'Failed to update booking'], 404);
        }
        return new BookingResource($booking);
    }

    public function destroy($id)
    {
        $result = $this->bookingService->deleteBooking($id);
        if (!$result) {
            return response()->json(['message' => 'Failed to delete booking'], 404);
        }
        return response()->json(['message' => 'Booking deleted successfully']);
    }
}
