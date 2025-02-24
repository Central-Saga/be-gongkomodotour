<?php

namespace App\Services\Implementation;

use App\Services\Contracts\BankAccountServiceInterface;
use App\Repositories\Contracts\BankAccountRepositoryInterface;

class BankAccountService implements BankAccountServiceInterface
{
    protected $repository;

    const BANK_ACCOUNTS_ALL_CACHE_KEY = 'bank_accounts.all';
    const BANK_ACCOUNTS_ACTIVE_CACHE_KEY = 'bank_accounts.active';
    const BANK_ACCOUNTS_INACTIVE_CACHE_KEY = 'bank_accounts.inactive';

    public function __construct(BankAccountRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllBankAccounts()
    {
        return Cache::remember(self::BANK_ACCOUNTS_ALL_CACHE_KEY, 3600, function () {
            return $this->repository->getAllBankAccounts();
        });
    }

    public function getBankAccountById($id)
    {
        return $this->repository->getBankAccountById($id);
    }

    public function getBankAccountByName($name)
    {
        return $this->repository->getBankAccountByName($name);
    }

    public function getBankAccountByStatus($status)
    {
        return $this->repository->getBankAccountByStatus($status);
    }

    public function getActiveBankAccounts()
    {
        return Cache::remember(self::BANK_ACCOUNTS_ACTIVE_CACHE_KEY, 60, function () {
            return $this->repository->getActiveBankAccounts();
        });
    }

    public function getInactiveBankAccounts()
    {
        return Cache::remember(self::BANK_ACCOUNTS_INACTIVE_CACHE_KEY, 60, function () {
            return $this->repository->getInactiveBankAccounts();
        });
    }

    public function createBankAccount(array $data)
    {
        $result = $this->repository->createBankAccount($data);

        $this->clearBankAccountCaches();

        return $result;
    }

    public function updateBankAccount($id, array $data)
    {
        $bankAccount = $this->getBankAccountById($id);

        if ($bankAccount) {
            $result = $this->repository->updateBankAccount($id, $data);

            $this->clearBankAccountCaches($id);

            return $result;
        }

        return null;
    }

    public function deleteBankAccount($id)
    {
        $result = $this->repository->deleteBankAccount($id);

        $this->clearBankAccountCaches($id);

        return $result;
    }

    public function updateBankAccountStatus($id, $status)
    {
        $bankAccount = $this->getBankAccountById($id);

        if ($bankAccount) {
            $result = $this->repository->updateBankAccountStatus($id, $status);

            $this->clearBankAccountCaches($id);

            return $result;
        }

        return null;
    }

    public function clearBankAccountCaches()
    {
        Cache::forget(self::BANK_ACCOUNTS_ALL_CACHE_KEY);
        Cache::forget(self::BANK_ACCOUNTS_ACTIVE_CACHE_KEY);
        Cache::forget(self::BANK_ACCOUNTS_INACTIVE_CACHE_KEY);
    }
}
