<?php

namespace App\Services\Implementation;

use App\Services\Contracts\BankAccountServiceInterface;
use App\Repositories\Contracts\BankAccountRepositoryInterface;

class BankAccountService implements BankAccountServiceInterface
{
    protected $repository;

    public function __construct(BankAccountRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    // Implementasi metode dari BankAccountServiceInterface
}
