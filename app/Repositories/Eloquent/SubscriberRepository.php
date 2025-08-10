<?php
// filepath: /c:/laragon/www/be-gongkomodotour/app/Repositories/Eloquent/SubscriberRepository.php

namespace App\Repositories\Eloquent;

use App\Models\Subscriber;
use App\Repositories\Contracts\SubscriberRepositoryInterface;

class SubscriberRepository implements SubscriberRepositoryInterface
{
    protected $model;

    public function __construct(Subscriber $subscriber)
    {
        $this->model = $subscriber;
    }

    /**
     * Mengambil semua subscriber.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAllSubscriber()
    {
        return $this->model->with('customer')->get();
    }

    /**
     * Mengambil subscriber berdasarkan ID.
     *
     * @param int $id
     * @return Subscriber|null
     */
    public function getSubscriberById($id)
    {
        return $this->model->find($id);
    }

    /**
     * Membuat subscriber baru.
     *
     * @param array $data
     * @return Subscriber
     */
    public function createSubscriber(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Memperbarui subscriber berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return Subscriber|null
     */
    public function updateSubscriber($id, array $data)
    {
        $subscriber = $this->model->find($id);
        if ($subscriber) {
            $subscriber->update($data);
        }
        return $subscriber;
    }

    /**
     * Menghapus subscriber berdasarkan ID.
     *
     * @param int $id
     * @return int
     */
    public function deleteSubscriber($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * Mengambil subscriber berdasarkan nama.
     *
     * @param string $name
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getSubscriberByName($name)
    {
        return $this->model->where('name', 'like', "%{$name}%")->get();
    }

    /**
     * Mengambil subscriber berdasarkan status.
     *
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getSubscriberByStatus($status)
    {
        return $this->model->where('status', $status)->get();
    }

    /**
     * Mencari subscriber berdasarkan kriteria tertentu.
     *
     * @param int $id
     * @return Subscriber|null
     */
    public function findSubscriber($id)
    {
        return $this->model->find($id);
    }
}
