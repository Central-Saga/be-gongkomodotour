<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\EmailBlastRecipientResource;
use App\Http\Requests\EmailBlastRecipientStoreRequest;
use App\Http\Requests\EmailBlastRecipientUpdateRequest;
use App\Services\Contracts\EmailBlastRecipientServiceInterface;

class EmailBlastRecipientController extends Controller
{
    /**
     * @var EmailBlastRecipientServiceInterface
     */
    protected $emailblastRecipientService;

    /**
     * Konstruktor EmailBlastRecipientController.
     */
    public function __construct(EmailBlastRecipientServiceInterface $emailblastRecipientService)
    {
        $this->emailblastRecipientService = $emailblastRecipientService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil parameter status dari query string
        // Berikut: pending, sent, failed
        $status = $request->query('status');

        if ($status === null) {
            // Jika tidak ada query parameter, ambil semua emailBlastRecipient
            $emailBlastRecipient = $this->emailblastRecipientService->getAllEmailBlastRecipient();
        } else {
            $status = (int) $request->query('status');;
            if ($status === 0) {
                $emailBlastRecipient = $this->emailblastRecipientService->getPendingEmailBlastRecipient();
            } elseif ($status === 1) {
                $emailBlastRecipient = $this->emailblastRecipientService->getSentEmailBlastRecipient();
            } elseif ($status === 2) {
                $emailBlastRecipient = $this->emailblastRecipientService->getFailedEmailBlastRecipient();
            } else {
                return response()->json(['error' => 'Invalid status parameter'], 400);
            }
        }
        return EmailBlastRecipientResource::collection($emailBlastRecipient);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmailBlastRecipientStoreRequest $request)
    {
        $emailBlastRecipient = $this->emailblastRecipientService->createEmailBlastRecipient($request->validated());
        return new EmailBlastRecipientResource($emailBlastRecipient);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $emailBlastRecipient = $this->emailblastRecipientService->getEmailBlastRecipientById($id);
        if (!$emailBlastRecipient) {
            return response()->json(['message' => 'EmailBlastRecipient not found'], 404);
        }
        return new EmailBlastRecipientResource($emailBlastRecipient);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmailBlastRecipientUpdateRequest $request, string $id)
    {
        $emailBlastRecipient = $this->emailblastRecipientService->updateEmailBlastRecipient($id, $request->validated());
        if (!$emailBlastRecipient) {
            return response()->json(['message' => 'EmailBlastRecipient not found'], 404);
        }
        return new EmailBlastRecipientResource($emailBlastRecipient);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->emailblastRecipientService->deleteEmailBlastRecipient($id);
        if (!$deleted) {
            return response()->json(['message' => 'EmailBlastRecipient not found'], 404);
        }
        return response()->json(['message' => 'EmailBlastRecipient deleted successfully'], 200);
    }

    /**
     * Update Status EmailBlastRecipient.
     */
    public function updateStatus(string $id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:Aktif,Non Aktif',
        ]);

        $emailBlastRecipient = $this->emailblastRecipientService->updateEmailBlastRecipientStatus($id, $request->validated());

        if (!$emailBlastRecipient) {
            return response()->json(['message' => 'Failed to update email blast recipient status'], 404);
        }
        return new EmailBlastRecipientResource($emailBlastRecipient);
    }
}
