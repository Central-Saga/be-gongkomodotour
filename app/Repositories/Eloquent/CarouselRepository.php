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
        return $this->model->with('assets')->get();
    }

    public function getById($id)
    {
        return $this->model->with('assets')->find($id);
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
            // Hapus assets terlebih dahulu
            $carousel->assets()->delete();
            $carousel->delete();
        }
        return $carousel;
    }

    public function getActive()
    {
        return $this->model->where('is_active', true)->with('assets')->get();
    }

    public function getInactive()
    {
        return $this->model->where('is_active', false)->with('assets')->get();
    }

    /**
     * Get carousel with specific assets count
     */
    public function getWithAssetsCount($count = 1)
    {
        return $this->model->withCount('assets')->having('assets_count', '>=', $count)->get();
    }

    /**
     * Get carousel by order
     */
    public function getByOrder()
    {
        return $this->model->orderBy('order_num', 'asc')->with('assets')->get();
    }
}
