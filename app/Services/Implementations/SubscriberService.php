<?php
// filepath: /c:/laragon/www/be-gongkomodotour/app/Services/Implementations/SubscriberService.php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\SubscriberServiceInterface;
use App\Repositories\Contracts\SubscriberRepositoryInterface;

class SubscriberService implements SubscriberServiceInterface
{
    protected $subscriberRepository;

    const SUBSCRIBER_ALL_CACHE_KEY      = 'subscriber.all';
    const SUBSCRIBER_ACTIVE_CACHE_KEY   = 'subscriber.active';
    const SUBSCRIBER_INACTIVE_CACHE_KEY = 'subscriber.inactive';

    /**
     * Konstruktor SubscriberService.
     *
     * @param SubscriberRepositoryInterface $subscriberRepository
     */
    public function __construct(SubscriberRepositoryInterface $subscriberRepository)
    {
        $this->subscriberRepository = $subscriberRepository;
    }

    /**
     * Mengambil semua subscriber.
     *
     * @return mixed
     */
    public function getAllSubscriber()
    {
        return Cache::remember(self::SUBSCRIBER_ALL_CACHE_KEY, 3600, function () {
            return $this->subscriberRepository->getAllSubscriber();
        });
    }

    /**
     * Mengambil subscriber berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getSubscriberById($id)
    {
        return $this->subscriberRepository->getSubscriberById($id);
    }

    /**
     * Mengambil subscriber berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getSubscriberByName($name)
    {
        return $this->subscriberRepository->getSubscriberByName($name);
    }

    /**
     * Mengambil subscriber berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getSubscriberByStatus($status)
    {
        return $this->subscriberRepository->getSubscriberByStatus($status);
    }

    /**
     * Mengambil semua subscriber yang aktif.
     *
     * @return mixed
     */
    public function getActiveSubscriber()
    {
        return Cache::remember(self::SUBSCRIBER_ACTIVE_CACHE_KEY, 3600, function () {
            return $this->subscriberRepository->getSubscriberByStatus(status: 'Aktif');
        });
    }

    /**
     * Mengambil semua subscriber yang tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveSubscriber()
    {
        return Cache::remember(self::SUBSCRIBER_INACTIVE_CACHE_KEY, 3600, function () {
            return $this->subscriberRepository->getSubscriberByStatus('Non Aktif');
        });
    }

    /**
     * Membuat subscriber baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createSubscriber(array $data)
    {
        $result = $this->subscriberRepository->createSubscriber($data);
        Cache::forget(self::SUBSCRIBER_ALL_CACHE_KEY);
        Cache::forget(self::SUBSCRIBER_ACTIVE_CACHE_KEY);
        Cache::forget(self::SUBSCRIBER_INACTIVE_CACHE_KEY);
        return $result;
    }

    /**
     * Memperbarui subscriber berdasarkan ID.
     *
     * @param int   $id
     * @param array $data
     * @return mixed
     */
    public function updateSubscriber($id, array $data)
    {
        $result = $this->subscriberRepository->updateSubscriber($id, $data);
        Cache::forget(self::SUBSCRIBER_ALL_CACHE_KEY);
        Cache::forget(self::SUBSCRIBER_ACTIVE_CACHE_KEY);
        Cache::forget(self::SUBSCRIBER_INACTIVE_CACHE_KEY);
        return $result;
    }

    /**
     * Menghapus subscriber berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteSubscriber($id)
    {
        $result = $this->subscriberRepository->deleteSubscriber($id);
        Cache::forget(self::SUBSCRIBER_ALL_CACHE_KEY);
        Cache::forget(self::SUBSCRIBER_ACTIVE_CACHE_KEY);
        Cache::forget(self::SUBSCRIBER_INACTIVE_CACHE_KEY);
        return $result;
    }
}