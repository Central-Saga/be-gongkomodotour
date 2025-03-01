<?php
// filepath: /c:/laragon/www/be-gongkomodotour/app/Http/Requests/TestimonialStoreRquest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestimonialStoreRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan untuk membuat testimonial.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk pembuatan testimonial.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'customer_id'  => 'required|exists:customers,id',
            'rating'       => 'required|integer|min:1|max:5',
            'review'       => 'required|string',
            'is_approved'  => 'required|boolean',
            'is_highlight' => 'required|boolean',
        ];
    }
}