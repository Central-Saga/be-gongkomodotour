<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BoatResource;
use App\Http\Requests\BoatStoreRequest;
use App\Http\Requests\BoatUpdateRequest;
use App\Services\Contracts\BoatServiceInterface;
use Spatie\Boat\Models\Boat;

class BoatController extends Controller
{
    /**
     * @var BoatServiceInterface $boatService
     */
    protected $boatService;

    /**
     * Konstruktor BoatController.
     */
    public function __construct(BoatServiceInterface $boatService)
    {
        $this->boatService = $boatService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil parameter status dari query string
        $status = $request->query('status');

        if ($status === null) {
            // Jika tidak ada query parameter, ambil semua boat
            $boat = $this->boatService->getAllBoat();
        } elseif ($status == 1) {
            // Jika status = 1, ambil boat dengan status aktif
            $boat = $this->boatService->getActiveBoat();
        } elseif ($status == 0) {
            // Jika status = 0 ambil boat dengan status tidak aktif
            $boat = $this->boatService->getInactiveBoat();
        } else {
            return response()->json(['error' => 'Invalid status parameter'], 400);
        }
        return BoatResource::collection($boat);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BoatStoreRequest $request)
    {
        $boat = $this->boatService->createBoat($request->validated());
        return new BoatResource($boat);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $boat = $this->boatService->getBoatById($id);
        if (!$boat) {
            return response()->json(['message' => 'Boat not found'], 404);
        }
        return new BoatResource($boat);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BoatUpdateRequest $request, string $id)
    {
        $boat = $this->boatService->updateBoat($id, $request->validated());
        if (!$boat) {
            return response()->json(['message' => 'Boat not found'], 404);
        }

        return new BoatResource($boat);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->boatService->deleteBoat($id);

        if (!$deleted) {
            return response()->json(['message' => 'Boat not found'], 404);
        }

        return response()->json(['message' => 'Boat deleted successfully'], 200);
    }

    /**
     * Get Active Boat.
     */
    public function getActiveBoat()
    {
        $boat = $this->boatService->getActiveBoat();
        // $boat = Boat::where('status', 'Aktif')->get();
        return BoatResource::collection($boat);
    }
}
