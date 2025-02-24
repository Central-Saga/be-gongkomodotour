<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CabinResource;
use App\Http\Requests\CabinStoreRequest;
use App\Http\Requests\CabinUpdateRequest;
use App\Services\Contracts\CabinServiceInterface;
use Spatie\Cabin\Models\Cabin;

class CabinController extends Controller
{
    /**
     * @var CabinServiceInterface $cabinService
     */
    protected $cabinService;

    /**
     * Konstruktor CabinController.
     */
    public function __construct(CabinServiceInterface $cabinService)
    {
        $this->cabinService = $cabinService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil parameter status dari query string
        $status = $request->query('status');

        if ($status === null) {
            // Jika tidak ada query parameter, ambil semua cabin
            $cabin = $this->cabinService->getAllCabin();
        } elseif ($status == 1) {
            // Jika status = 1, ambil cabin dengan status aktif
            $cabin = $this->cabinService->getActiveCabin();
        } elseif ($status == 0) {
            // Jika status = 0 ambil cabin dengan status tidak aktif
            $cabin = $this->cabinService->getInactiveCabin();
        } else {
            return response()->json(['error' => 'Invalid status parameter'], 400);
        }
        return CabinResource::collection($cabin);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CabinStoreRequest $request)
    {
        $cabin = $this->cabinService->createCabin($request->validated());
        return new CabinResource($cabin);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cabin = $this->cabinService->getCabinById($id);
        if (!$cabin) {
            return response()->json(['message' => 'Cabin not found'], 404);
        }
        return new CabinResource($cabin);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CabinUpdateRequest $request, string $id)
    {
        $cabin = $this->cabinService->updateCabin($id, $request->validated());
        if (!$cabin) {
            return response()->json(['message' => 'Cabin not found'], 404);
        }

        return new CabinResource($cabin);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->cabinService->deleteCabin($id);

        if (!$deleted) {
            return response()->json(['message' => 'Cabin not found'], 404);
        }

        return response()->json(['message' => 'Cabin deleted successfully'], 200);
    }

    /**
     * Get Active Cabin.
     */
    public function getActiveCabin()
    {
        $cabin = $this->cabinService->getActiveCabin();
        // $cabin = Cabin::where('status', 'Aktif')->get();
        return CabinResource::collection($cabin);
    }

    /**
     * Update Status Cabin.
     */
    public function updateStatus(string $id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:Aktif,Non Aktif',
        ]);

        $cabin = $this->cabinService->updateCabinStatus($id, $request->validated());

        if (!$cabin) {
            return response()->json(['message' => 'Failed to update cabin status'], 404);
        }
        return new CabinResource($cabin);
    }
}
