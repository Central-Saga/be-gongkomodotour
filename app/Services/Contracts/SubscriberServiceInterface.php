<?php
// filepath: /c:/laragon/www/be-gongkomodotour/app/Services/Contracts/SubscriberServiceInterface.php

namespace App\Services\Contracts;

interface SubscriberServiceInterface
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
     * Mengambil semua subscriber yang aktif.
     *
     * @return mixed
     */
    public function getActiveSubscriber();

    /**
     * Mengambil semua subscriber yang tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveSubscriber();
}