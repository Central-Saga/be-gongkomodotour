<?php
// filepath: /c:/laragon/www/be-gongkomodotour/app/Http/Requests/FaqUpdateRquest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaqUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk update FAQ.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'question'      => 'sometimes|required|string|max:255',
            'answer'        => 'sometimes|required|string',
            'category'      => 'sometimes|required|string|in:Umum,Pembayaran,Pemesanan,Pembatalan,Lainnya',
            'display_order' => 'sometimes|required|integer|min:1|max:6',
            'status'        => 'sometimes|required|in:Aktif,Non Aktif',
        ];
    }
}
