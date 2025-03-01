<?php
// filepath: /c:/laragon/www/be-gongkomodotour/app/Http/Requests/TestimonialUpdateRquest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestimonialUpdateRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan untuk melakukan update testimonial.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk update testimonial.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'customer_id'  => 'sometimes|required|exists:customers,id',
            'rating'       => 'sometimes|required|integer|min:1|max:5',
            'review'       => 'sometimes|required|string',
            'is_approved'  => 'sometimes|required|boolean',
            'is_highlight' => 'sometimes|required|boolean',
        ];
    }
}