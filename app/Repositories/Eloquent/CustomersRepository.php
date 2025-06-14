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
        return $this->model->with('user')->get();
        Log::info($this->model->with('user')->get());
    }

    public function getCustomerById($id)
    {
        return $this->model->with('user')->findOrFail($id);
    }

    public function createCustomer(array $data)
    {
        $customer = $this->model->create($data);
        return $customer->load('user');
    }

    public function updateCustomer($id, array $data)
    {
        $customer = $this->findCustomer($id);

        if ($customer) {
            $customer->update($data);
            $customer->load('user');
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
