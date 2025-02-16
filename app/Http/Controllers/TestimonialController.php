<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\TestimonialResource;
use App\Http\Requests\TestimonialStoreRequest;
use App\Http\Requests\TestimonialUpdateRequest;
use App\Services\Contracts\TestimonialServiceInterface;

class TestimonialController extends Controller
{
    protected $testimonialService;

    public function __construct(TestimonialServiceInterface $testimonialService)
    {
        $this->testimonialService = $testimonialService;
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