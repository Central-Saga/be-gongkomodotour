<?php

namespace App\Repositories\Contracts;

interface ItinerariesRepositoryInterface
{
    /**
     * Mengambil semua itineraries.
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

    /**
     * Menghapus itineraries yang tidak ada di trip.
     *
     * @param int $trip_id
     * @return mixed
     */
    public function deleteItinerariesNotIn($trip_id, $existing_id);
}
