<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\BankAccountRepositoryInterface;

class BankAccountRepository implements BankAccountRepositoryInterface
{
    /**
     * @var BankAccount
     */
    protected $model;

    /**
     * Konstruktor BankAccountRepository.
     *
     * @param BankAccount $model
     */
    public function __construct(BankAccount $model)
    {
        $this->model = $model;
    }

    /**
     * Mengambil semua data bank account.
     *
     * @return mixed
     */
    public function getAllBankAccounts()
    {
        return $this->model->all();
    }

    /**
     * Mengambil data bank account berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getBankAccountById($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Bank account with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil data bank account berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getBankAccountByName($name)
    {
        return $this->model->where('name', $name)->first();
    }

    /**
     * Mengambil data bank account berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getBankAccountByStatus($status)
    {
        return $this->model->where('status', $status)->get();
    }

    /**
     * Membuat data bank account baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createBankAccount(array $data)
    {
        try {
            return $this->model->create($data);
        } catch (\Exception $e) {
            Log::error("Failed to create bank account: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Memperbarui data bank account berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateBankAccount($id, array $data)
    {
        $bankAccount = $this->findBankAccount($id);

        if ($bankAccount) {
            try {
                $bankAccount->update($data);
                return $bankAccount;
            } catch (\Exception $e) {
                Log::error("Failed to update bank account with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus data bank account berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteBankAccount($id)
    {
        $bankAccount = $this->findBankAccount($id);

        if ($bankAccount) {
            try {
                $bankAccount->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete bank account with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan bank account berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findBankAccount($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Bank account with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengupdate bank account status.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateBankAccountStatus($id, $status)
    {
        $bankAccount = $this->findBankAccount($id);

        if ($bankAccount) {
            $bankAccount->status = $status;
            $bankAccount->save();
            return $bankAccount;
        }
        return null;
    }
}
