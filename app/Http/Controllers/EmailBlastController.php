<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\EmailBlastResource;
use App\Http\Requests\EmailBlastStoreRequest;
use App\Http\Requests\EmailBlastUpdateRequest;
use App\Services\Contracts\EmailBlastServiceInterface;
use Spatie\EmailBlast\Models\EmailBlast;

class EmailBlastController extends Controller
{
    /**
     * @var EmailBlastServiceInterface $emailblastService
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
        // Ambil parameter status dari query string
        $status = $request->query('status');

        if ($status === null) {
            // Jika tidak ada query parameter, ambil semua emailBlast
            $emailBlast = $this->emailblastService->getAllEmailBlast();
        } elseif ($status == 1) {
            // Jika status = 1, ambil emailBlast dengan status aktif
            $emailBlast = $this->emailblastService->getActiveEmailBlast();
        } elseif ($status == 0) {
            // Jika status = 0 ambil emailBlast dengan status tidak aktif
            $emailBlast = $this->emailblastService->getInactiveEmailBlast();
        } else {
            return response()->json(['error' => 'Invalid status parameter'], 400);
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
     * Get Active EmailBlast.
     */
    public function getActiveEmailBlast()
    {
        $emailBlast = $this->emailblastService->getActiveEmailBlast();
        // $emailBlast = EmailBlast::where('status', 'Aktif')->get();
        return EmailBlastResource::collection($emailBlast);
    }
}
