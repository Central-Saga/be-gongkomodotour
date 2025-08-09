<?php

namespace App\Services\Implementations;

use App\Services\Contracts\CarouselServiceInterface;
use App\Repositories\Contracts\CarouselRepositoryInterface;

class CarouselService implements CarouselServiceInterface
{
    protected $repository;

    public function __construct(CarouselRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllCarousel()
    {
        return $this->repository->getAll();
    }

    public function getCarouselById($id)
    {
        return $this->repository->getById($id);
    }

    public function createCarousel(array $data)
    {
        return $this->repository->create($data);
    }

    public function updateCarousel($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function deleteCarousel($id)
    {
        return $this->repository->delete($id);
    }

    public function getActiveCarousel()
    {
        return $this->repository->getActive();
    }

    public function getInactiveCarousel()
    {
        return $this->repository->getInactive();
    }
}
