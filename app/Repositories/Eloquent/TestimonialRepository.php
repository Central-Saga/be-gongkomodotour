<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\TestimonialRepositoryInterface;
use App\Models\Testimonial;

class TestimonialRepository implements TestimonialRepositoryInterface
{
    public function getAllTestimonial()
    {
        return Testimonial::all();
    }

    public function getTestimonialById($id)
    {
        return Testimonial::find($id);
    }

    public function createTestimonial(array $data)
    {
        return Testimonial::create($data);
    }

    public function updateTestimonial($id, array $data)
    {
        $testimonial = Testimonial::find($id);
        if ($testimonial) {
            $testimonial->update($data);
        }
        return $testimonial;
    }

    public function deleteTestimonial($id)
    {
        $testimonial = Testimonial::find($id);
        if ($testimonial) {
            $testimonial->delete();
            return true;
        }
        return false;
    }

    public function findTestimonial($id)
    {
        return Testimonial::find($id);
    }

    public function getTestimonialByApproved($approved)
    {
        return Testimonial::where('is_approved', $approved)->get();
    }

    public function getTestimonialByHighlight($highlight)
    {
        return Testimonial::where('is_highlight', $highlight)->get();
    }

    public function getTestimonialByFilters($approved = null, $highlight = null)
    {
        $query = Testimonial::query();


        if (!is_null($approved)) {
            $query->where('is_approved', (bool) $approved);
        }
    
        if (!is_null($highlight)) {
            $query->where('is_highlight', (bool) $highlight);
        }

        return $query->get();
    }
}