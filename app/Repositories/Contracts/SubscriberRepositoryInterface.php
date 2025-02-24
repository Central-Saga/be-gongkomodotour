<?php

namespace App\Repositories\Contracts;

interface SubscriberRepositoryInterface
{
    /**
     * Mengambil semua subscriber.
     *
     * @return mixed
     */
    public function getAllSubscriber();

    /**
     * Mengambil subscriber berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getSubscriberById($id);

    /**
     * Membuat subscriber baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createSubscriber(array $data);

    /**
     * Memperbarui subscriber berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateSubscriber($id, array $data);

    /**
     * Menghapus subscriber berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteSubscriber($id);

    /**
     * Mengambil subscriber berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getSubscriberByName($name);

    /**
     * Mengambil subscriber berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getSubscriberByStatus($status);

    /**
     * Mencari subscriber berdasarkan kriteria tertentu.
     *
     * @param int $id
     * @return mixed
     */
    public function findSubscriber($id);
}