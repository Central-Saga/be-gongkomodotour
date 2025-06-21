<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use App\Http\Resources\TripResource;
use App\Http\Requests\TripStoreRequest;
use App\Http\Requests\TripUpdateRequest;
use App\Services\Contracts\TripServiceInterface;
use Illuminate\Support\Facades\Log;

class TripController extends Controller
{
    protected $tripService;

    /**
     * Get the middleware the controller should use.
     *
     * @return array
     */

    /**
     * Konstruktor TripController.
     */
    public function __construct(TripServiceInterface $tripService)
    {
        $this->tripService = $tripService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = $request->query('type');

        if ($type) {
            if (strtolower($type) === 'open') {
                $trips = $this->tripService->getOpenTrips();
            } elseif (strtolower($type) === 'private') {
                $trips = $this->tripService->getPrivateTrips();
            } else {
                return response()->json(['message' => 'Invalid type parameter'], 400);
            }
        } elseif ($request->has('status')) {
            $status = $request->query('status');
            if (strtolower($status) == '1') {
                $trips = $this->tripService->getActiveTrips();
            } elseif (strtolower($status) == '0') {
                $trips = $this->tripService->getInactiveTrips();
            } else {
                return response()->json(['message' => 'Invalid status parameter'], 400);
            }
        } else {
            $trips = $this->tripService->getAllTrips();
        }

        if (!$trips) {
            return response()->json(['message' => 'Trip tidak ditemukan'], 404);
        }
        return TripResource::collection($trips);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TripStoreRequest $request)
    {
        Log::info('TripController::store called with validated data:', $request->validated());

        $trip = $this->tripService->createTrip($request->validated());
        if (!$trip) {
            return response()->json(['message' => 'Gagal membuat trip'], 400);
        }
        return new TripResource($trip);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $trip = $this->tripService->getTripById($id);
        if (!$trip) {
            return response()->json(['message' => 'Trip tidak ditemukan'], 404);
        }
        return new TripResource($trip);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TripUpdateRequest $request, string $id)
    {
        $trip = $this->tripService->updateTrip($id, $request->validated());
        if (!$trip) {
            return response()->json(['message' => 'Trip tidak ditemukan'], 404);
        }
        return new TripResource($trip);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->tripService->deleteTrip($id);

        if (!$deleted) {
            return response()->json(['message' => 'Trip tidak ditemukan'], 404);
        }
        return response()->json(['message' => 'Trip berhasil dihapus'], 200);
    }

    /**
     * Update Status Trip.
     */
    public function updateStatus(string $id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:Aktif,Non Aktif',
        ]);

        $trip = $this->tripService->updateTripStatus($id, $request->validated());

        if (!$trip) {
            return response()->json(['message' => 'Failed to update trip status'], 404);
        }
        return new TripResource($trip);
    }

    /**
     * Get highlighted trips.
     */
    public function getHighlightedTrips()
    {
        $trips = $this->tripService->getHighlightedTrips();
        if (!$trips) {
            return response()->json(['message' => 'Trip tidak ditemukan'], 404);
        }
        return TripResource::collection($trips);
    }
}
