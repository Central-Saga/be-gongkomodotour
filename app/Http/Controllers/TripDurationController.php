<?php

namespace App\Http\Controllers;

use App\Models\TripDuration;
use Illuminate\Http\Request;
use App\Http\Resources\TripDurationResource;
use App\Http\Requests\TripDurationStoreRequest;
use App\Http\Requests\TripDurationUpdateRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Services\Contracts\TripDurationServiceInterface;

class TripDurationController extends Controller implements HasMiddleware
{
    protected $tripDurationService;

    /**
     * Get the middleware the controller should use.
     *
     * @return array
     */
    public static function middleware()
    {
        return ['permission:mengelola trip duration'];
    }

    /**
     * Konstruktor TripDurationController.
     */
    public function __construct(TripDurationServiceInterface $tripDurationService)
    {
        $this->tripDurationService = $tripDurationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        if ($status === null) {
            $tripDurations = $this->tripDurationService->getAllTripDurations();
        } elseif ($status == 1) {
            $tripDurations = $this->tripDurationService->getActiveTripDurations();
        } elseif ($status == 0) {
            $tripDurations = $this->tripDurationService->getInactiveTripDurations();
        } else {
            return response()->json(['error' => 'Invalid status parameter'], 400);
        }

        if (!$tripDurations) {
            return response()->json(['message' => 'Trip duration tidak ditemukan'], 404);
        }

        return TripDurationResource::collection($tripDurations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TripDurationStoreRequest $request)
    {
        $tripDuration = $this->tripDurationService->createTripDuration($request->all());
        if (!$tripDuration) {
            return response()->json(['message' => 'Gagal membuat trip duration'], 400);
        }
        return new TripDurationResource($tripDuration);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tripDuration = $this->tripDurationService->getTripDurationById($id);
        if (!$tripDuration) {
            return response()->json(['message' => 'Trip duration tidak ditemukan'], 404);
        }
        return new TripDurationResource($tripDuration);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TripDurationUpdateRequest $request, string $id)
    {
        $tripDuration = $this->tripDurationService->updateTripDuration($id, $request->all());
        if (!$tripDuration) {
            return response()->json(['message' => 'Trip duration tidak ditemukan'], 404);
        }
        return new TripDurationResource($tripDuration);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->tripDurationService->deleteTripDuration($id);

        if (!$deleted) {
            return response()->json(['message' => 'Trip duration tidak ditemukan'], 404);
        }
        return response()->json(['message' => 'Trip duration berhasil dihapus'], 200);
    }
}
