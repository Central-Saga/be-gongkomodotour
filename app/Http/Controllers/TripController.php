<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use App\Http\Resources\TripResource;
use App\Http\Requests\TripStoreRequest;
use App\Http\Requests\TripUpdateRequest;
use App\Services\Contracts\TripServiceInterface;
use Illuminate\Routing\Controllers\HasMiddleware;

class TripController extends Controller implements HasMiddleware
{
    protected $tripService;

    /**
     * Get the middleware the controller should use.
     *
     * @return array
     */
    public static function middleware()
    {
        return ['permission:mengelola trips'];
    }

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
        // Ambil parameter status dari query string
        $status = $request->query('status');

        if ($status === null) {
            // Jika tidak ada query parameter, ambil semua trip
            $trips = $this->tripService->getAllTrips();
        } elseif ($status == 1) {
            // Jika status = 1, ambil trip dengan status aktif
            $trips = $this->tripService->getActiveTrips();
        } elseif ($status == 0) {
            // Jika status = 0, ambil trip dengan status tidak aktif
            $trips = $this->tripService->getInactiveTrips();
        } else {
            return response()->json(['error' => 'Invalid status parameter'], 400);
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
        $trip = $this->tripService->createTrip($request->all());
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
        $trip = $this->tripService->updateTrip($id, $request->all());
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
}
