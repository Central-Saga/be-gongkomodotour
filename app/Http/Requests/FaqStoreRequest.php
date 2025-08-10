<?php
// filepath: /c:/laragon/www/be-gongkomodotour/app/Http/Requests/FaqStoreRquest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaqStoreRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan untuk membuat FAQ.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'question'      => 'required|string|max:255',
            'answer'        => 'required|string',
            'category'      => 'required|string|in:Umum,Pembayaran,Pemesanan,Pembatalan,Lainnya',
            'display_order' => 'required|integer|min:1|max:6',
            'status'        => 'required|in:Aktif,Non Aktif',
        ];
    }
}
