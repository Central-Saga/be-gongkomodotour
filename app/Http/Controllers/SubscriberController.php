<?php
// filepath: /c:/laragon/www/be-gongkomodotour/app/Http/Controllers/SubscriberController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriberResource;
use App\Http\Requests\SubscriberStoreRequest;
use App\Http\Requests\SubscriberUpdateRequest;
use App\Services\Contracts\SubscriberServiceInterface;

class SubscriberController extends Controller
{
    /**
     * @var SubscriberServiceInterface
     */
    protected $subscriberService;

    /**
     * Konstruktor SubscriberController.
     */
    public function __construct(SubscriberServiceInterface $subscriberService)
    {
        $this->subscriberService = $subscriberService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil parameter status dari query string
        $status = $request->query('status');

        if ($status === null) {
            // Jika tidak ada query parameter, ambil semua subscriber
            $subscriber = $this->subscriberService->getAllSubscriber();
        } elseif ($status == 1) {
            // Jika status = 1, ambil subscriber dengan status aktif
            $subscriber = $this->subscriberService->getActiveSubscriber();
        } elseif ($status == 0) {
            // Jika status = 0, ambil subscriber dengan status tidak aktif
            $subscriber = $this->subscriberService->getInactiveSubscriber();
        } else {
            return response()->json(['error' => 'Invalid status parameter'], 400);
        }
        return SubscriberResource::collection($subscriber);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubscriberStoreRequest $request)
    {
        $subscriber = $this->subscriberService->createSubscriber($request->validated());
        return new SubscriberResource($subscriber);
    }
    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subscriber = $this->subscriberService->getSubscriberById($id);
        if (!$subscriber) {
            return response()->json(['message' => 'Subscriber not found'], 404);
        }
        return new SubscriberResource($subscriber);
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(SubscriberUpdateRequest $request, string $id)
    {
        $subscriber = $this->subscriberService->updateSubscriber($id, $request->validated());
        if (!$subscriber) {
            return response()->json(['message' => 'Subscriber not found'], 404);
        }
        return new SubscriberResource($subscriber);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->subscriberService->deleteSubscriber($id);
        if (!$deleted) {
            return response()->json(['message' => 'Subscriber not found'], 404);
        }
        return response()->json(['message' => 'Subscriber deleted successfully'], 200);
    }
    
    /**
     * Get Active Subscriber.
     */
    public function getActiveSubscriber()
    {
        $subscriber = $this->subscriberService->getActiveSubscriber();
        return SubscriberResource::collection($subscriber);
    }
    
    /**
     * Get Inactive Subscriber.
     */
    public function getInactiveSubscriber()
    {
        $subscriber = $this->subscriberService->getInactiveSubscriber();
        return SubscriberResource::collection($subscriber);
    }
}