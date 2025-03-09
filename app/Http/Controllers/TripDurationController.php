<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TripDurationServiceInterface;

class TripDurationController extends Controller
{
    protected $tripDurationService;


    public function __construct(TripDurationServiceInterface $tripDurationService)
    {
        $this->tripDurationService = $tripDurationService;
    }

    public function updateStatus(string $id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:Aktif,Non Aktif',
        ]);

        $tripDuration = $this->tripDurationService->updateTripDurationStatus($id, $request->validated());

        if (!$tripDuration) {
            return response()->json(['message' => 'Failed to update trip duration status'], 404);
        }
        return new TripDurationResource($tripDuration);
    }
}
