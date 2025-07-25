<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\CustomersServiceInterface;
use App\Repositories\Contracts\CustomersRepositoryInterface;


class CustomersService implements CustomersServiceInterface
{
    protected $customerRepository;

    const CUSTOMERS_ALL_CACHE_KEY = 'customers.all';
    const CUSTOMERS_ACTIVE_CACHE_KEY = 'customers.active';
    const CUSTOMERS_INACTIVE_CACHE_KEY = 'customers.inactive';

    /**
     * Konstruktor CustomerService.
     *
     * @param CustomersRepositoryInterface $customerRepository
     */
    public function __construct(CustomersRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Mengambil semua customers.
     *
     * @return mixed
     */
    public function getAllCustomers()
    {
        return Cache::remember(self::CUSTOMERS_ALL_CACHE_KEY, 3600, function () {
            return $this->customerRepository->getAllCustomers();
        });
    }

    /**
     * Mengambil customer berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getCustomerById($id)
    {
        return $this->customerRepository->getCustomerById($id);
    }

    /**
     * Mengambil customer berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getCustomerByName($name)
    {
        return $this->customerRepository->getCustomerByName($name);
    }

    /**
     * Mengambil customer berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getCustomerByStatus($status)
    {
        return $this->customerRepository->getCustomerByStatus($status);
    }

    /**
     * Mengambil customers dengan status aktif.
     *
     * @return mixed
     */
    public function getActiveCustomers()
    {
        return Cache::remember(self::CUSTOMERS_ACTIVE_CACHE_KEY, 3600, function () {
            return $this->customerRepository->getCustomerByStatus('Aktif');
        });
    }

    /**
     * Mengambil customers dengan status tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveCustomers()
    {
        return Cache::remember(self::CUSTOMERS_INACTIVE_CACHE_KEY, 3600, function () {
            return $this->customerRepository->getCustomerByStatus('Non Aktif');
        });
    }

    /**
     * Membuat customer baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createCustomer(array $data)
    {
        $result = $this->customerRepository->createCustomer($data);
        $this->clearCustomerCaches();
        return $result;
    }

    /**
     * Memperbarui customer berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateCustomer($id, array $data)
    {
        $result = $this->customerRepository->updateCustomer($id, $data);
        $this->clearCustomerCaches();
        return $result;
    }

    /**
     * Menghapus customer berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteCustomer($id)
    {
        $result = $this->customerRepository->deleteCustomer($id);
        $this->clearCustomerCaches();

        return $result;
    }

    public function updateCustomerStatus($id, $status)
    {
        $customer = $this->getCustomerById($id);

        if ($customer) {
            $result = $this->customerRepository->updateCustomerStatus($id, $status);

            $this->clearCustomerCaches($id);

            return $result;
        }

        return null;
    }

    /**
     * Menghapus semua cache customer
     *
     * @return void
     */
    public function clearCustomerCaches()
    {
        Cache::forget(self::CUSTOMERS_ALL_CACHE_KEY);
        Cache::forget(self::CUSTOMERS_ACTIVE_CACHE_KEY);
        Cache::forget(self::CUSTOMERS_INACTIVE_CACHE_KEY);
    }
}
