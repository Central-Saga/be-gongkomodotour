<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\FlightScheduleResource;
use App\Http\Requests\FlightScheduleStoreRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Http\Requests\FlightScheduleUpdateRequest;
use App\Services\Contracts\FlightScheduleServiceInterface;

class FlightScheduleController extends Controller implements HasMiddleware
{
    protected $flightScheduleService;

    /**
     * Get the middleware the controller should use.
     *
     * @return array
     */
    public static function middleware()
    {
        return ['permission:mengelola jadwal penerbangan'];
    }

    /**
     * Konstruktor FlightScheduleController.
     */
    public function __construct(FlightScheduleServiceInterface $flightScheduleService)
    {
        $this->flightScheduleService = $flightScheduleService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flightSchedules = $this->flightScheduleService->getAllFlightSchedules();
        if (!$flightSchedules) {
            return response()->json(['message' => 'Jadwal penerbangan tidak ditemukan'], 404);
        }
        return FlightScheduleResource::collection($flightSchedules);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FlightScheduleStoreRequest $request)
    {
        $flightSchedule = $this->flightScheduleService->createFlightSchedule($request->all());
        if (!$flightSchedule) {
            return response()->json(['message' => 'Gagal membuat jadwal penerbangan'], 400);
        }
        return new FlightScheduleResource($flightSchedule);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $flightSchedule = $this->flightScheduleService->getFlightScheduleById($id);
        if (!$flightSchedule) {
            return response()->json(['message' => 'Jadwal penerbangan tidak ditemukan'], 404);
        }
        return new FlightScheduleResource($flightSchedule);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FlightScheduleUpdateRequest $request, string $id)
    {
        $flightSchedule = $this->flightScheduleService->updateFlightSchedule($id, $request->all());
        if (!$flightSchedule) {
            return response()->json(['message' => 'Jadwal penerbangan tidak ditemukan'], 404);
        }
        return new FlightScheduleResource($flightSchedule);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->flightScheduleService->deleteFlightSchedule($id);
        if (!$deleted) {
            return response()->json(['message' => 'Jadwal penerbangan tidak ditemukan'], 404);
        }
        return response()->json(['message' => 'Jadwal penerbangan berhasil dihapus']);
    }
}
