<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BankAccountResource;
use App\Http\Requests\BankAccountStoreRequest;
use App\Http\Requests\BankAccountUpdateRequest;
use App\Services\Contracts\BankAccountServiceInterface;
use Illuminate\Routing\Controllers\HasMiddleware;

class BankAccountController extends Controller implements HasMiddleware
{
    protected $bankAccountService;

    public static function middleware()
    {
        return [
            'permission:mengelola bank_accounts',
        ];
    }

    public function __construct(BankAccountServiceInterface $bankAccountService)
    {
        $this->bankAccountService = $bankAccountService;
    }

    public function updateStatus(string $id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:Aktif,Non Aktif',
        ]);

        $bankAccount = $this->bankAccountService->updateBankAccountStatus($id, $request->validated());

        if (!$bankAccount) {
            return response()->json(['message' => 'Failed to update bank account status'], 404);
        }
        return new BankAccountResource($bankAccount);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        if ($status) {
            if (strtolower($status) === '1') {
                $bankAccounts = $this->bankAccountService->getActiveBankAccounts();
            } elseif (strtolower($status) === '0') {
                $bankAccounts = $this->bankAccountService->getInactiveBankAccounts();
            } else {
                return response()->json(['message' => 'Invalid status parameter'], 404);
            }
        } else {
            $bankAccounts = $this->bankAccountService->getAllBankAccounts();
        }
        return BankAccountResource::collection($bankAccounts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BankAccountStoreRequest $request)
    {
        $bankAccount = $this->bankAccountService->createBankAccount($request->validated());
        if (!$bankAccount) {
            return response()->json(['message' => 'Failed to create bank account'], 404);
        }
        return new BankAccountResource($bankAccount);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bankAccount = $this->bankAccountService->getBankAccountById($id);
        if (!$bankAccount) {
            return response()->json(['message' => 'Bank account not found'], 404);
        }
        return new BankAccountResource($bankAccount);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BankAccountUpdateRequest $request, string $id)
    {
        $bankAccount = $this->bankAccountService->updateBankAccount($id, $request->validated());
        if (!$bankAccount) {
            return response()->json(['message' => 'Failed to update bank account'], 404);
        }
        return new BankAccountResource($bankAccount);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bankAccount = $this->bankAccountService->deleteBankAccount($id);
        if (!$bankAccount) {
            return response()->json(['message' => 'Failed to delete bank account'], 404);
        }
        return response()->json(['message' => 'Bank account deleted successfully'], 200);
    }
}
