<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\BookingResource;
use App\Http\Requests\BookingStoreRequest;
use App\Http\Requests\BookingUpdateRequest;
use App\Services\Contracts\BookingServiceInterface;
use App\Services\Contracts\TripServiceInterface;
use App\Services\Contracts\CabinServiceInterface;
use App\Services\Contracts\BoatServiceInterface;
use App\Http\Resources\TripResource;
use App\Http\Resources\CabinResource;
use App\Http\Resources\BoatResource;

class BookingController extends Controller
{
    protected $bookingService;
    protected $tripService;
    protected $cabinService;
    protected $boatService;

    public function __construct(BookingServiceInterface $bookingService, TripServiceInterface $tripService, CabinServiceInterface $cabinService, BoatServiceInterface $boatService)
    {
        $this->bookingService = $bookingService;
        $this->tripService = $tripService;
        $this->cabinService = $cabinService;
        $this->boatService = $boatService;
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

    /**
     * Update Status Booking.
     */
    public function updateStatus(string $id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:Pending,Confirmed,Cancelled',
        ]);

        $booking = $this->bookingService->updateBookingStatus($id, $request->validated());

        if (!$booking) {
            return response()->json(['message' => 'Failed to update booking status'], 404);
        }
        return new BookingResource($booking);
    }

    /**
     * Get booking page data
     */
    public function getBookingPage(Request $request)
    {
        $request->validate([
            'type' => 'required|in:open,private',
            'packageId' => 'required|exists:trips,id',
            'date' => 'required|date',
        ]);

        $trip = $this->tripService->getTripById($request->packageId);
        if (!$trip) {
            return response()->json(['message' => 'Trip not found'], 404);
        }

        // Get available cabins for the selected date
        $availableCabins = $this->cabinService->getAvailableCabins($request->date);

        // Get available boats for the selected date
        $availableBoats = $this->boatService->getAvailableBoats($request->date);

        return response()->json([
            'trip' => new TripResource($trip),
            'available_cabins' => CabinResource::collection($availableCabins),
            'available_boats' => BoatResource::collection($availableBoats),
            'selected_date' => $request->date,
            'type' => $request->type
        ]);
    }
}
