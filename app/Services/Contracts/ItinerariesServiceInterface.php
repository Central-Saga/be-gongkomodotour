<?php

namespace App\Services\Contracts;

interface ItinerariesServiceInterface
{
    /**
     * Mengambil semua itineraries.
     *
     * @return mixed
     */
    public function getAllItineraries();

    /**
     * Mengambil itinerary berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getItineraryById($id);

    /**
     * Mengambil itinerary berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getItineraryByName($name);

    /**
     * Mengambil itinerary berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getItineraryByStatus($status);

    /**
     * Mengambil itinerary berdasarkan trip id.
     *
     * @param int $trip_id
     * @return mixed
     */
    public function getItineraryByTripId($trip_id);

    /**
     * Membuat itinerary baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createItinerary(array $data);

    /**
     * Memperbarui itinerary berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateItinerary($id, array $data);

    /**
     * Menghapus itinerary berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteItinerary($id);

    /**
     * Mengupdate status itinerary.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateItineraryStatus($id, $status);
}
