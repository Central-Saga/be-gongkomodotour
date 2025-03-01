<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HotelRequestServiceInterface;
use App\Http\Middleware\HasMiddleware;
use App\Http\Middleware\Middleware;

class HotelRequestController extends Controller implements HasMiddleware
{
    protected $hotelRequestService;

    public static function middleware()
    {
        return [
            'permission:mengelola hotel_requests',
        ];
    }

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
