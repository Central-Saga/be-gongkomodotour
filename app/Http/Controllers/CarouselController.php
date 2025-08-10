<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CarouselResource;
use App\Http\Requests\CarouselStoreRequest;
use App\Http\Requests\CarouselUpdateRequest;
use App\Services\Contracts\CarouselServiceInterface;


class CarouselController extends Controller
{
    /**
     * @var CarouselServiceInterface $carouselService
     */
    protected $carouselService;

    /**
     * Konstruktor CarouselController.
     */
    public function __construct(CarouselServiceInterface $carouselService)
    {
        $this->carouselService = $carouselService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        if ($status === null) {
            $carousel = $this->carouselService->getAllCarousel();
        } elseif ($status == 1) {
            $carousel = $this->carouselService->getActiveCarousel();
        } elseif ($status == 0) {
            $carousel = $this->carouselService->getInactiveCarousel();
        } else {
            return response()->json(['error' => 'Invalid status parameter'], 400);
        }
        return CarouselResource::collection($carousel);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CarouselStoreRequest $request)
    {
        $carousel = $this->carouselService->createCarousel($request->validated());
        return new CarouselResource($carousel);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $carousel = $this->carouselService->getCarouselById($id);
        if (!$carousel) {
            return response()->json(['message' => 'Carousel not found'], 404);
        }
        return new CarouselResource($carousel);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarouselUpdateRequest $request, string $id)
    {
        $carousel = $this->carouselService->updateCarousel($id, $request->validated());
        if (!$carousel) {
            return response()->json(['message' => 'Carousel not found'], 404);
        }
        return new CarouselResource($carousel);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->carouselService->deleteCarousel($id);
        if (!$deleted) {
            return response()->json(['message' => 'Carousel not found'], 404);
        }
        return response()->json(['message' => 'Carousel deleted successfully'], 200);
    }
}
