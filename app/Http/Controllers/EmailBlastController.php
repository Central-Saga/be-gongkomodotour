<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\EmailBlastResource;
use App\Http\Requests\EmailBlastStoreRequest;
use App\Http\Requests\EmailBlastUpdateRequest;
use App\Services\Contracts\EmailBlastServiceInterface;

class EmailBlastController extends Controller
{
    /**
     * @var EmailBlastServiceInterface
     */
    protected $emailblastService;

    /**
     * Konstruktor EmailBlastController.
     */
    public function __construct(EmailBlastServiceInterface $emailblastService)
    {
        $this->emailblastService = $emailblastService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil parameter status dari query string (contoh: draft, pending, scheduled, failed)
        $status = $request->query('status');

        if ($status === null) {
            // Jika tidak ada query parameter, ambil semua emailBlast
            $emailBlast = $this->emailblastService->getAllEmailBlast();
        } else {
            // Bandingkan status secara case-insensitive
            $status = (int) $request->query('status');
            if ($status === 0) {
                $emailBlast = $this->emailblastService->getDraftEmailBlast();
            } elseif ($status === 1) {
                $emailBlast = $this->emailblastService->getSentEmailBlast();
            } elseif ($status === 2) {
                $emailBlast = $this->emailblastService->getScheduledEmailBlast();
            } elseif ($status === 3) {
                $emailBlast = $this->emailblastService->getFailedEmailBlast();
            } else {
                return response()->json(['error' => 'Invalid status parameter'], 400);
            }
        }
        return EmailBlastResource::collection($emailBlast);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmailBlastStoreRequest $request)
    {
        $emailBlast = $this->emailblastService->createEmailBlast($request->validated());
        return new EmailBlastResource($emailBlast);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $emailBlast = $this->emailblastService->getEmailBlastById($id);
        if (!$emailBlast) {
            return response()->json(['message' => 'EmailBlast not found'], 404);
        }
        return new EmailBlastResource($emailBlast);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmailBlastUpdateRequest $request, string $id)
    {
        $emailBlast = $this->emailblastService->updateEmailBlast($id, $request->validated());
        if (!$emailBlast) {
            return response()->json(['message' => 'EmailBlast not found'], 404);
        }
        return new EmailBlastResource($emailBlast);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->emailblastService->deleteEmailBlast($id);
        if (!$deleted) {
            return response()->json(['message' => 'EmailBlast not found'], 404);
        }
        return response()->json(['message' => 'EmailBlast deleted successfully'], 200);
    }

    /**
     * Update Status EmailBlast.
     */
    public function updateStatus(string $id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:draft,scheduled,sent,failed',
        ]);

        $emailBlast = $this->emailblastService->updateEmailBlastStatus($id, $request->validated());

        if (!$emailBlast) {
            return response()->json(['message' => 'Failed to update email blast status'], 404);
        }
        return new EmailBlastResource($emailBlast);
    }
}
