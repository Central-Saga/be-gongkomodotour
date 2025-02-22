<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\BankAccountRepositoryInterface;

class BankAccountRepository implements BankAccountRepositoryInterface
{
    protected $model;

    public function __construct(BankAccount $model)
    {
        $this->model = $model;
    }
}
