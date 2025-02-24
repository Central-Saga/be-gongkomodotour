<?php

namespace App\Services\Implementations;

use App\Services\Contracts\HotelRequestServiceInterface;
use App\Repositories\Contracts\HotelRequestRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class HotelRequestService implements HotelRequestServiceInterface
{
    protected $hotelRequestRepository;

    const HOTEL_REQUEST_ALL_CACHE_KEY = 'hotel_requests.all';
    const HOTEL_REQUEST_WAITING_CONFIRMATION_CACHE_KEY = 'hotel_requests.waiting_confirmation';
    const HOTEL_REQUEST_ACCEPTED_CACHE_KEY = 'hotel_requests.accepted';
    const HOTEL_REQUEST_REJECTED_CACHE_KEY = 'hotel_requests.rejected';

    public function __construct(HotelRequestRepositoryInterface $hotelRequestRepository)
    {
        $this->hotelRequestRepository = $hotelRequestRepository;
    }

    public function getAllHotelRequests()
    {
        $hotelRequests = Cache::remember(self::HOTEL_REQUEST_ALL_CACHE_KEY, 3600, function () {
            return $this->hotelRequestRepository->getAllHotelRequests();
        });

        return $hotelRequests;
    }

    public function getHotelRequestById($id)
    {
        return $this->hotelRequestRepository->getHotelRequestById($id);
    }

    public function getHotelRequestByName($name)
    {
        return $this->hotelRequestRepository->getHotelRequestByName($name);
    }

    public function getHotelRequestByStatus($status)
    {
        return $this->hotelRequestRepository->getHotelRequestByStatus($status);
    }

    public function getHotelRequestByStatusWaitingConfirmation()
    {
        $hotelRequests = Cache::remember(self::HOTEL_REQUEST_WAITING_CONFIRMATION_CACHE_KEY, 3600, function () {
            return $this->hotelRequestRepository->getHotelRequestByStatus('Menunggu Konfirmasi');
        });

        return $hotelRequests;
    }

    public function getHotelRequestByStatusAccepted()
    {
        $hotelRequests = Cache::remember(self::HOTEL_REQUEST_ACCEPTED_CACHE_KEY, 3600, function () {
            return $this->hotelRequestRepository->getHotelRequestByStatus('Diterima');
        });

        return $hotelRequests;
    }

    public function getHotelRequestByStatusRejected()
    {
        $hotelRequests = Cache::remember(self::HOTEL_REQUEST_REJECTED_CACHE_KEY, 3600, function () {
            return $this->hotelRequestRepository->getHotelRequestByStatus('Ditolak');
        });

        return $hotelRequests;
    }

    public function createHotelRequest(array $data)
    {
        $result = $this->hotelRequestRepository->createHotelRequest($data);
        $this->clearHotelRequestCaches();
        return $result;
    }

    public function updateHotelRequest($id, array $data)
    {
        $result = $this->hotelRequestRepository->updateHotelRequest($id, $data);
        $this->clearHotelRequestCaches();
        return $result;
    }

    public function deleteHotelRequest($id)
    {
        $result = $this->hotelRequestRepository->deleteHotelRequest($id);
        $this->clearHotelRequestCaches();
        return $result;
    }

    public function updateHotelRequestStatus($id, $status)
    {
        $result = $this->hotelRequestRepository->updateHotelRequestStatus($id, $status);
        $this->clearHotelRequestCaches();
        return $result;
    }

    public function clearHotelRequestCaches()
    {
        Cache::forget(self::HOTEL_REQUEST_ALL_CACHE_KEY);
        Cache::forget(self::HOTEL_REQUEST_WAITING_CONFIRMATION_CACHE_KEY);
        Cache::forget(self::HOTEL_REQUEST_ACCEPTED_CACHE_KEY);
        Cache::forget(self::HOTEL_REQUEST_REJECTED_CACHE_KEY);
    }
}
