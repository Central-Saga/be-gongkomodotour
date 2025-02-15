<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Contracts\ItinerariesServiceInterface;
use App\Http\Resources\ItineraryResource;
use App\Http\Requests\ItineraryStoreRequest;
use App\Http\Requests\ItineraryUpdateRequest;
use Illuminate\Routing\Controllers\HasMiddleware;

class ItinerariesController extends Controller implements HasMiddleware
{
    protected $itinerariesService;

    /**
     * Get the middleware the controller should use.
     *
     * @return array
     */
    public static function middleware()
    {
        return ['permission:mengelola itineraries'];
    }

    /**
     * Konstruktor ItinerariesController.
     */
    public function __construct(ItinerariesServiceInterface $itinerariesService)
    {
        $this->itinerariesService = $itinerariesService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $itineraries = $this->itinerariesService->getAllItineraries();
        if (!$itineraries) {
            return response()->json(['message' => 'Itinerary tidak ditemukan'], 404);
        }
        return ItineraryResource::collection($itineraries);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItineraryStoreRequest $request)
    {
        $itinerary = $this->itinerariesService->createItinerary($request->all());
        if (!$itinerary) {
            return response()->json(['message' => 'Gagal membuat itinerary'], 400);
        }
        return new ItineraryResource($itinerary);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $itinerary = $this->itinerariesService->getItineraryById($id);
        if (!$itinerary) {
            return response()->json(['message' => 'Itinerary tidak ditemukan'], 404);
        }
        return new ItineraryResource($itinerary);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ItineraryUpdateRequest $request, string $id)
    {
        $itinerary = $this->itinerariesService->updateItinerary($id, $request->all());
        if (!$itinerary) {
            return response()->json(['message' => 'Itinerary tidak ditemukan'], 404);
        }
        return new ItineraryResource($itinerary);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->itinerariesService->deleteItinerary($id);

        if (!$deleted) {
            return response()->json(['message' => 'Itinerary tidak ditemukan'], 404);
        }
        return response()->json(['message' => 'Itinerary berhasil dihapus'], 200);
    }
}
