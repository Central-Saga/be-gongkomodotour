<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\CarouselRepositoryInterface;

class CarouselRepository implements CarouselRepositoryInterface
{
    protected $model;

    public function __construct(\App\Models\Carousel $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $carousel = $this->getById($id);
        if ($carousel) {
            $carousel->update($data);
        }
        return $carousel;
    }

    public function delete($id)
    {
        $carousel = $this->getById($id);
        if ($carousel) {
            $carousel->delete();
        }
        return $carousel;
    }

    public function getActive()
    {
        return $this->model->where('is_active', true)->get();
    }

    public function getInactive()
    {
        return $this->model->where('is_active', false)->get();
    }
}
