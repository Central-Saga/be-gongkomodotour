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
            'customer_name'    => 'sometimes|required|string|max:255',
            'customer_email'   => 'sometimes|nullable|email|max:255',
            'customer_phone'   => 'sometimes|nullable|string|max:20',
            'trip_id'          => 'sometimes|nullable|exists:trips,id',
            'rating'           => 'sometimes|required|integer|min:1|max:5',
            'review'           => 'sometimes|required|string',
            'is_approved'      => 'sometimes|boolean',
            'is_highlight'     => 'sometimes|boolean',
            'source'           => 'sometimes|string|in:internal',
        ];
    }

    /**
     * Pesan validasi kustom.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_name.required' => 'Nama customer wajib diisi.',
            'customer_name.max'      => 'Nama customer maksimal 255 karakter.',
            'customer_email.email'   => 'Format email tidak valid.',
            'rating.required'        => 'Rating wajib diisi.',
            'rating.min'             => 'Rating minimal 1.',
            'rating.max'             => 'Rating maksimal 5.',
            'review.required'        => 'Review wajib diisi.',
            'source.in'              => 'Source harus internal.',
        ];
    }
}
