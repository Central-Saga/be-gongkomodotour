<?php
// filepath: /c:/laragon/www/be-gongkomodotour/app/Http/Controllers/FaqController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\FaqResource;
use App\Http\Requests\FaqStoreRequest;
use App\Http\Requests\FaqUpdateRequest;
use App\Services\Contracts\FaqServiceInterface;

class FaqController extends Controller
{
    /**
     * @var FaqServiceInterface
     */
    protected $faqService;

    /**
     * Konstruktor FaqController.
     *
     * @param FaqServiceInterface $faqService
     */
    public function __construct(FaqServiceInterface $faqService)
    {
        $this->faqService = $faqService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil parameter status dari query string
        $status = $request->query('status');

        if ($status === null) {
            // Jika tidak ada query parameter, ambil semua FAQ
            $faqs = $this->faqService->getAllFaq();
        } elseif ($status == 1) {
            // Jika status = 1, ambil FAQ dengan status aktif
            $faqs = $this->faqService->getActiveFaq();
        } elseif ($status == 0) {
            // Jika status = 0, ambil FAQ dengan status tidak aktif
            $faqs = $this->faqService->getInactiveFaq();
        } else {
            return response()->json(['error' => 'Invalid status parameter'], 400);
        }

        return FaqResource::collection($faqs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FaqStoreRequest $request)
    {
        $faq = $this->faqService->createFaq($request->validated());
        return new FaqResource($faq);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $faq = $this->faqService->getFaqById($id);
        if (!$faq) {
            return response()->json(['message' => 'FAQ not found'], 404);
        }
        return new FaqResource($faq);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FaqUpdateRequest $request, string $id)
    {
        $faq = $this->faqService->updateFaq($id, $request->validated());
        if (!$faq) {
            return response()->json(['message' => 'FAQ not found'], 404);
        }
        return new FaqResource($faq);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->faqService->deleteFaq($id);

        if (!$deleted) {
            return response()->json(['message' => 'FAQ not found'], 404);
        }

        return response()->json(['message' => 'FAQ deleted successfully'], 200);
    }
}