<?php

namespace App\Repositories\Eloquent;

use App\Models\Customers;
use App\Repositories\Contracts\CustomersRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomersRepository implements CustomersRepositoryInterface
{
    public function getAllCustomers()
    {
        return Customers::all();
    }

    public function getCustomerById($id)
    {
        return Customers::findOrFail($id);
    }

    public function createCustomer(array $data)
    {
        return Customers::create($data);
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
        return Customers::where('name', $name)->first();
    }

    public function getCustomerByStatus($status)
    {
        return Customers::where('status', $status)->get();
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
}