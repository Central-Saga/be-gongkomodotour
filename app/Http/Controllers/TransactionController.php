<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\TransactionResource;
use App\Http\Requests\TransactionStoreRequest;
use App\Http\Requests\TransactionUpdateRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Services\Contracts\TransactionServiceInterface;

class TransactionController extends Controller implements HasMiddleware
{
    protected $transactionService;

    public static function middleware()
    {
        return [
            'permission:mengelola transaksi',
        ];
    }

    public function __construct(TransactionServiceInterface $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->has('status')) {
            $status = $request->query('status');
            if (strtolower($status) == '0') {
                $transactions = $this->transactionService->getWaitingTransactions();
            } elseif (strtolower($status) == '1') {
                $transactions = $this->transactionService->getPaidTransactions();
            } elseif (strtolower($status) == '2') {
                $transactions = $this->transactionService->getRejectedTransactions();
            } else {
                return response()->json(['message' => 'Invalid status parameter'], 404);
            }
        } else {
            $transactions = $this->transactionService->getAllTransactions();
        }
        return TransactionResource::collection($transactions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionStoreRequest $request)
    {
        $transaction = $this->transactionService->createTransaction($request->all());
        if (!$transaction) {
            return response()->json(['message' => 'Failed to create transaction'], 404);
        }
        return new TransactionResource($transaction);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction = $this->transactionService->getTransactionById($id);
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }
        return new TransactionResource($transaction);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionUpdateRequest $request, string $id)
    {
        $transaction = $this->transactionService->updateTransaction($id, $request->all());
        if (!$transaction) {
            return response()->json(['message' => 'Failed to update transaction'], 404);
        }
        return new TransactionResource($transaction);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->transactionService->deleteTransaction($id);
        if (!$result) {
            return response()->json(['message' => 'Failed to delete transaction'], 404);
        }
        return response()->json(['message' => 'Transaction deleted successfully']);
    }
}
