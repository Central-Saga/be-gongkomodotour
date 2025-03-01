<?php

namespace App\Services\Implementations;

use App\Services\Contracts\DetailTransactionServiceInterface;
use App\Repositories\Contracts\DetailTransactionRepositoryInterface;

class DetailTransactionService implements DetailTransactionServiceInterface
{
    protected $detailTransactionRepository;

    const DETAIL_TRANSACTION_ALL_CACHE_KEY = 'detail_transactions.all';

    public function __construct(DetailTransactionRepositoryInterface $detailTransactionRepository)
    {
        $this->detailTransactionRepository = $detailTransactionRepository;
    }

    public function getAllDetailTransactions()
    {
        $detailTransactions = Cache::remember(self::DETAIL_TRANSACTION_ALL_CACHE_KEY, 3600, function () {
            return $this->detailTransactionRepository->getAllDetailTransactions();
        });

        return $detailTransactions;
    }

    public function getDetailTransactionById($id)
    {
        return $this->detailTransactionRepository->getDetailTransactionById($id);
    }

    public function getDetailTransactionByName($name)
    {
        return $this->detailTransactionRepository->getDetailTransactionByName($name);
    }

    public function getDetailTransactionByStatus($status)
    {
        return $this->detailTransactionRepository->getDetailTransactionByStatus($status);
    }

    public function createDetailTransaction(array $data)
    {
        $result = $this->detailTransactionRepository->createDetailTransaction($data);
        $this->clearDetailTransactionCaches();
        return $result;
    }

    public function updateDetailTransaction($id, array $data)
    {
        $result = $this->detailTransactionRepository->updateDetailTransaction($id, $data);
        $this->clearDetailTransactionCaches();
        return $result;
    }

    public function deleteDetailTransaction($id)
    {
        $result = $this->detailTransactionRepository->deleteDetailTransaction($id);
        $this->clearDetailTransactionCaches();
        return $result;
    }

    public function updateDetailTransactionStatus($id, $status)
    {
        $result = $this->detailTransactionRepository->updateDetailTransactionStatus($id, $status);
        $this->clearDetailTransactionCaches();
        return $result;
    }

    public function clearDetailTransactionCaches()
    {
        Cache::forget(self::DETAIL_TRANSACTION_ALL_CACHE_KEY);
    }
}
