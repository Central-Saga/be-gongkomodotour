<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AdditionalServiceInterface;

class AdditionalController extends Controller
{
    protected $additionalService;

    public function __construct(AdditionalServiceInterface $additionalService)
    {
        $this->additionalService = $additionalService;
    }


    public function updateStatus(string $id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:Aktif,Non Aktif',
        ]);

        $additional = $this->additionalService->updateAdditionalStatus($id, $request->validated());

        if (!$additional) {
            return response()->json(['message' => 'Failed to update additional status'], 404);
        }
        return new AdditionalResource($additional);
    }
}
