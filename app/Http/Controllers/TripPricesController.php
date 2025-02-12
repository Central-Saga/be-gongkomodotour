<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Contracts\TripPricesServiceInterface;
use App\Http\Resources\TripPricesResource;
use App\Http\Requests\TripPricesStoreRequest;
use App\Http\Requests\TripPricesUpdateRequest;
use Illuminate\Routing\Controllers\HasMiddleware;

class TripPricesController extends Controller implements HasMiddleware
{
    protected $tripPricesService;

    /**
     * Get the middleware the controller should use.
     *
     * @return array
     */
    public static function middleware()
    {
        return ['permission:mengelola harga trip'];
    }

    /**
     * Konstruktor TripPricesController.
     */
    public function __construct(TripPricesServiceInterface $tripPricesService)
    {
        $this->tripPricesService = $tripPricesService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tripPrices = $this->tripPricesService->getAllTripPrices();
        if (!$tripPrices) {
            return response()->json(['message' => 'Harga trip tidak ditemukan'], 404);
        }
        return TripPricesResource::collection($tripPrices);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TripPricesStoreRequest $request)
    {
        $tripPrices = $this->tripPricesService->createTripPrices($request->all());
        if (!$tripPrices) {
            return response()->json(['message' => 'Gagal membuat harga trip'], 400);
        }
        return new TripPricesResource($tripPrices);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tripPrices = $this->tripPricesService->getTripPricesById($id);
        if (!$tripPrices) {
            return response()->json(['message' => 'Harga trip tidak ditemukan'], 404);
        }
        return new TripPricesResource($tripPrices);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TripPricesUpdateRequest $request, string $id)
    {
        $tripPrices = $this->tripPricesService->updateTripPrices($id, $request->all());

        if (!$tripPrices) {
            return response()->json(['message' => 'Harga trip tidak ditemukan'], 404);
        }

        return new TripPricesResource($tripPrices);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->tripPricesService->deleteTripPrices($id);

        if (!$deleted) {
            return response()->json(['message' => 'Harga trip tidak ditemukan'], 404);
        }

        return response()->json(['message' => 'Harga trip berhasil dihapus']);
    }
}
