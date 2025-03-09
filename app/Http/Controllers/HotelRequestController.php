<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HotelRequestServiceInterface;

class HotelRequestController extends Controller
{
    protected $hotelRequestService;

    public function __construct(HotelRequestServiceInterface $hotelRequestService)
    {
        $this->hotelRequestService = $hotelRequestService;
    }

    public function updateStatus(string $id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:Menunggu Konfirmasi,Diterima,Ditolak',
        ]);

        $hotelRequest = $this->hotelRequestService->updateHotelRequestStatus($id, $request->validated());

        if (!$hotelRequest) {
            return response()->json(['message' => 'Failed to update hotel request status'], 404);
        }
        return new HotelRequestResource($hotelRequest);
    }
}
