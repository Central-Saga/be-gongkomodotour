<?php

namespace App\Repositories\Contracts;

interface CustomersRepositoryInterface
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
     * Mencari customer berdasarkan kriteria tertentu.
     *
     * @param int $id
     * @return mixed
     */
    public function findCustomer($id);

    /**
     * Mengupdate customer status.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateCustomerStatus($id, $status);
}
