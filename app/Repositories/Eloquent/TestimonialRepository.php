<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\TestimonialRepositoryInterface;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TestimonialRepository implements TestimonialRepositoryInterface
{
    protected $model;

    public function __construct(Testimonial $testimonial)
    {
        $this->model = $testimonial;
    }

    public function getAllTestimonial()
    {
        return $this->model->with('customer', 'customer.user', 'trip')->get();
    }

    public function getTestimonialById($id)
    {
        return $this->model->with('customer', 'customer.user', 'trip')->find($id);
    }

    public function createTestimonial(array $data)
    {
        $testimonial = $this->model->create($data);
        $testimonial->load('customer', 'customer.user', 'trip');
        return $testimonial;
    }

    public function updateTestimonial($id, array $data)
    {
        $testimonial = $this->findTestimonial($id);

        if ($testimonial) {
            $testimonial->update($data);
            $testimonial->load('customer', 'customer.user', 'trip');
        }

        return $testimonial;
    }

    public function deleteTestimonial($id)
    {
        $testimonial = $this->findTestimonial($id);

        if ($testimonial) {
            $testimonial->delete();
            $testimonial->load('customer', 'customer.user', 'trip');
            return true;
        }

        return false;
    }

    public function findTestimonial($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Testimonial with ID {$id} not found.");
            return null;
        }
    }

    public function getTestimonialByApproved($approved)
    {
        return $this->model->where('is_approved', $approved)->with('customer', 'customer.user', 'trip')->get();
    }

    public function getTestimonialByHighlight($highlight)
    {
        return $this->model->where('is_highlight', $highlight)->with('customer', 'customer.user', 'trip')->get();
    }

    public function getTestimonialByFilters($approved = null, $highlight = null)
    {
        $query = $this->model->newQuery();

        if (!is_null($approved)) {
            $query->where('is_approved', (bool) $approved);
        }

        if (!is_null($highlight)) {
            $query->where('is_highlight', (bool) $highlight);
        }

        return $query->with('customer', 'customer.user', 'trip')->get();
    }
}
