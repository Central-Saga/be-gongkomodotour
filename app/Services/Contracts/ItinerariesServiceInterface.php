<?php

namespace App\Services\Contracts;

interface ItinerariesServiceInterface
{
    /**
     * Mengambil semua itinerariess.
     *
     * @return mixed
     */
    public function getAllItineraries();

    /**
     * Mengambil itineraries berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getItinerariesById($id);

    /**
     * Mengambil itineraries berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getItinerariesByName($name);

    /**
     * Mengambil itineraries berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getItinerariesByStatus($status);

    /**
     * Mengambil semua itinerariess yang aktif.
     *
     * @return mixed
     */
    public function getActiveItinerariess();

    /**
     * Mengambil semua itinerariess yang tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveItinerariess();

    /**
     * Membuat itineraries baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createItineraries(array $data);

    /**
     * Memperbarui itineraries berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateItineraries($id, array $data);

    /**
     * Menghapus itineraries berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteItineraries($id);
}
