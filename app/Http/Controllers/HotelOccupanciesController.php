<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\HotelOccupancyResource;
use App\Http\Requests\HotelOccupanciesStoreRequest;
use App\Http\Requests\HotelOccupanciesUpdateRequest;
use App\Services\Contracts\HotelOccupanciesServiceInterface;

class HotelOccupanciesController extends Controller
{
    /**
     * @var HotelOccupanciesServiceInterface
     */
    protected $hotelOccupanciesService;

    /**
     * Konstruktor HotelOccupanciesController.
     *
     * @param HotelOccupanciesServiceInterface $hotelOccupanciesService
     */
    public function __construct(HotelOccupanciesServiceInterface $hotelOccupanciesService)
    {
        $this->hotelOccupanciesService = $hotelOccupanciesService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil parameter status dari query string
        $status = $request->query('status');

        if ($status === null) {
            // Jika tidak ada query parameter, ambil semua hotel occupancies
            $hotelOccupancies = $this->hotelOccupanciesService->getAllHotelOccupancies();
        } elseif ($status == 1) {
            // Jika status = 1, ambil hotel occupancy dengan status aktif
            $hotelOccupancies = $this->hotelOccupanciesService->getActiveHotelOccupancies();
        } elseif ($status == 0) {
            // Jika status = 0 ambil hotel occupancy dengan status tidak aktif
            $hotelOccupancies = $this->hotelOccupanciesService->getInactiveHotelOccupancies();
        } else {
            return response()->json(['error' => 'Invalid status parameter'], 400);
        }
        return HotelOccupancyResource::collection($hotelOccupancies);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(HotelOccupanciesStoreRequest $request)
    {
        $hotelOccupancy = $this->hotelOccupanciesService->createHotelOccupancies($request->validated());
        return response()->json(new HotelOccupancyResource($hotelOccupancy), status: 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $hotelOccupancy = $this->hotelOccupanciesService->getHotelOccupanciesById($id);
        if (!$hotelOccupancy) {
            return response()->json(['message' => 'Hotel Occupancy not found'], 404);
        }
        return new HotelOccupancyResource($hotelOccupancy);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HotelOccupanciesUpdateRequest $request, string $id)
    {
        $hotelOccupancy = $this->hotelOccupanciesService->updateHotelOccupancies($id, $request->validated());
        if (!$hotelOccupancy) {
            return response()->json(['message' => 'Hotel Occupancy not found'], 404);
        }
        return new HotelOccupancyResource($hotelOccupancy);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->hotelOccupanciesService->deleteHotelOccupancies($id);
        if (!$deleted) {
            return response()->json(['message' => 'Hotel Occupancy not found'], 404);
        }
        return response()->json(['message' => 'Hotel Occupancy deleted successfully'], 200);
    }

    /**
     * Get Active Hotel Occupancies.
     */
    public function getActiveHotelOccupancies()
    {
        $hotelOccupancies = $this->hotelOccupanciesService->getActiveHotelOccupancies();
        return HotelOccupancyResource::collection($hotelOccupancies);
    }

    /**
     * Update Status Hotel Occupancy.
     */
    public function updateStatus(string $id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:Aktif,Non Aktif',
        ]);

        $hotelOccupancy = $this->hotelOccupanciesService->updateHotelOccupanciesStatus($id, $request->validated());

        if (!$hotelOccupancy) {
            return response()->json(['message' => 'Failed to update hotel occupancy status'], 404);
        }
        return new HotelOccupancyResource($hotelOccupancy);
    }
}
