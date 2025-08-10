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
            'customer_name'    => 'required|string|max:255',
            'customer_email'   => 'nullable|email|max:255',
            'customer_phone'   => 'nullable|string|max:20',
            'trip_id'          => 'nullable|exists:trips,id',
            'rating'           => 'required|integer|min:1|max:5',
            'review'           => 'required|string',
            'is_approved'      => 'boolean',
            'is_highlight'     => 'boolean',
            'source'           => 'string|in:internal',
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
