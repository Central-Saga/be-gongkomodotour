<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TripPricesServiceInterface;

class TripPricesController extends Controller
{
    protected $tripPricesService;

    public function __construct(TripPricesServiceInterface $tripPricesService)
    {
        $this->tripPricesService = $tripPricesService;
    }

    public function updateStatus(string $id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:Aktif,Non Aktif',
        ]);

        $tripPrices = $this->tripPricesService->updateTripPricesStatus($id, $request->validated());

        if (!$tripPrices) {
            return response()->json(['message' => 'Failed to update trip prices status'], 404);
        }
        return new TripPricesResource($tripPrices);
    }
}
