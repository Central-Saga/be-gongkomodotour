<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\HotelRequestRepositoryInterface;

class HotelRequestRepository implements HotelRequestRepositoryInterface
{
    /**
     * @var HotelRequest
     */
    protected $model;

    /**
     * Constructor
     *
     * @param HotelRequest $model
     */
    public function __construct(HotelRequest $model)
    {
        $this->model = $model;
    }

    /**
     * Mengambil semua permintaan hotel.
     *
     * @return mixed
     */
    public function getAllHotelRequests()
    {
        return $this->model->all();
    }

    /**
     * Mengambil permintaan hotel berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getHotelRequestById($id)
    {
        return $this->model->find($id);
    }

    /**
     * Mengambil permintaan hotel berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getHotelRequestByName($name)
    {
        return $this->model->where('name', $name)->first();
    }

    /**
     * Mengambil permintaan hotel berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getHotelRequestByStatus($status)
    {
        return $this->model->where('status', $status)->get();
    }

    /**
     * Membuat permintaan hotel baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createHotelRequest(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Memperbarui permintaan hotel berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateHotelRequest($id, array $data)
    {
        return $this->model->find($id)->update($data);
    }

    /**
     * Menghapus permintaan hotel berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteHotelRequest($id)
    {
        return $this->model->find($id)->delete();
    }

    /**
     * Mengupdate permintaan hotel status.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateHotelRequestStatus($id, $status)
    {
        $hotelRequest = $this->findHotelRequest($id);

        if ($hotelRequest) {
            $hotelRequest->status = $status;
            $hotelRequest->save();
            return $hotelRequest;
        }
        return null;
    }
}
