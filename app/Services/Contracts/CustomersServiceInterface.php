<?php

namespace App\Services\Contracts;

interface CustomersServiceInterface
{
    /**
     * Mengambil semua customers.
     *
     * @return mixed
     */
    public function getAllCustomers();

    /**
     * Mengambil customer berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getCustomerById($id);

    /**
     * Membuat customer baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createCustomer(array $data);

    /**
     * Memperbarui customer berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateCustomer($id, array $data);

    /**
     * Menghapus customer berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteCustomer($id);

    /**
     * Mengambil customer berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getCustomerByName($name);

    /**
     * Mengambil customer berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getCustomerByStatus($status);
    
    /**
     * Mengambil semua customers yang aktif.
     *
     * @return mixed
     */
    public function getActiveCustomers();

    /**
     * Mengambil semua customers yang tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveCustomers();
}