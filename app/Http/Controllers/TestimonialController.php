<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\TestimonialResource;
use App\Http\Requests\TestimonialStoreRequest;
use App\Http\Requests\TestimonialUpdateRequest;
use App\Services\Contracts\TestimonialServiceInterface;
use App\Services\GooglePlacesService;

class TestimonialController extends Controller
{
    protected $testimonialService;
    protected $googlePlacesService;

    public function __construct(
        TestimonialServiceInterface $testimonialService,
        GooglePlacesService $googlePlacesService
    ) {
        $this->testimonialService = $testimonialService;
        $this->googlePlacesService = $googlePlacesService;
    }

    public function index(Request $request)
    {
        // Ambil parameter dari query string
        $approved = $request->query('approved');
        $highlight = $request->query('highlight');

        // Konversi parameter ke boolean jika ada
        $approved = $approved !== null ? (bool)$approved : null;
        $highlight = $highlight !== null ? (bool)$highlight : null;

        // Ambil testimonial berdasarkan filter
        $testimonials = $this->testimonialService->getTestimonialByFilters($approved, $highlight);

        return TestimonialResource::collection($testimonials);
    }

    /**
     * Ambil semua testimonial (internal + Google reviews)
     */
    public function getAllTestimonials(Request $request)
    {
        $googleLimit = $request->query('google_limit', 5);
        $internalLimit = $request->query('internal_limit', 10);

        $testimonials = $this->googlePlacesService->getAllTestimonials($googleLimit, $internalLimit);

        return response()->json([
            'success' => true,
            'data' => $testimonials,
            'meta' => [
                'total' => count($testimonials),
                'google_count' => collect($testimonials)->where('source', 'google_review')->count(),
                'internal_count' => collect($testimonials)->where('source', 'internal')->count(),
            ]
        ]);
    }

    /**
     * Ambil testimonial yang di-highlight
     */
    public function getHighlightedTestimonials(Request $request)
    {
        $limit = $request->query('limit', 5);

        $testimonials = $this->googlePlacesService->getHighlightedTestimonials($limit);

        return response()->json([
            'success' => true,
            'data' => $testimonials,
            'meta' => [
                'total' => count($testimonials),
            ]
        ]);
    }

    /**
     * Ambil Google Reviews saja
     */
    public function getGoogleReviews(Request $request)
    {
        $limit = $request->query('limit', 5);

        $reviews = $this->googlePlacesService->getLatestReviews($limit);

        return response()->json([
            'success' => true,
            'data' => $reviews,
            'meta' => [
                'total' => count($reviews),
            ]
        ]);
    }

    public function store(TestimonialStoreRequest $request)
    {
        $testimonial = $this->testimonialService->createTestimonial($request->validated());
        return new TestimonialResource($testimonial);
    }

    public function show(string $id)
    {
        $testimonial = $this->testimonialService->getTestimonialById($id);
        if (!$testimonial) {
            return response()->json(['message' => 'Testimonial not found'], 404);
        }
        return new TestimonialResource($testimonial);
    }

    public function update(TestimonialUpdateRequest $request, string $id)
    {
        $testimonial = $this->testimonialService->updateTestimonial($id, $request->validated());
        if (!$testimonial) {
            return response()->json(['message' => 'Testimonial not found'], 404);
        }
        return new TestimonialResource($testimonial);
    }

    public function destroy(string $id)
    {
        $deleted = $this->testimonialService->deleteTestimonial($id);
        if (!$deleted) {
            return response()->json(['message' => 'Testimonial not found'], 404);
        }
        return response()->json(['message' => 'Testimonial deleted successfully'], 200);
    }
}
