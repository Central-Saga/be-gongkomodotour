<?php

namespace App\Repositories\Eloquent;

use App\Models\Customers;
use App\Repositories\Contracts\CustomersRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomersRepository implements CustomersRepositoryInterface
{
    protected $model;

    public function __construct(Customers $customer)
    {
        $this->model = $customer;
    }

    public function getAllCustomers()
    {
        return $this->model->all();
    }

    public function getCustomerById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function createCustomer(array $data)
    {
        return $this->model->create($data);
    }

    public function updateCustomer($id, array $data)
    {
        $customer = $this->findCustomer($id);

        if ($customer) {
            $customer->update($data);
        }

        return $customer;
    }

    public function deleteCustomer($id)
    {
        $customer = $this->findCustomer($id);

        if ($customer) {
            $customer->delete();
        }

        return $customer;
    }

    public function getCustomerByName($name)
    {
        return $this->model->where('name', $name)->first();
    }

    public function getCustomerByStatus($status)
    {
        return $this->model->where('status', $status)->get();
    }
    
    public function findCustomer($id)
    {
        try {
            return $this->model->findOrFail($id);

    /**
     * Mencari customer berdasarkan kriteria tertentu (helper berdasarkan ID).
     *
     * @param int $id
     * @return Customers|null
     */
    public function findCustomer($id)
    {
        try {
            return Customers::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Customer with ID {$id} not found.");
            return null;
        }
    }
}

    /**
     * Mengupdate customer status.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateCustomerStatus($id, $status)
    {
        $customer = $this->findCustomer($id);

        if ($customer) {
            $customer->status = $status;
            $customer->save();
            return $customer;
        }
        return null;
    }
}
