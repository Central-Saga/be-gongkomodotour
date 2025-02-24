<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TripPricesServiceInterface;
use App\Http\Middleware\HasMiddleware;
use App\Http\Middleware\Middleware;

class TripPricesController extends Controller implements HasMiddleware
{
    protected $tripPricesService;

    public static function middleware()
    {
        return [
            'permission:mengelola trip_prices',
        ];
    }

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
