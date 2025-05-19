<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'booking_id' => 'required|exists:bookings,id',
            'bank_type' => 'nullable|in:BCA,Mandiri',
            'total_amount' => 'required|numeric',
            'payment_status' => 'required|in:Menunggu Pembayaran,Lunas,Ditolak',

            'hotel_request_details' => 'nullable|array',
            'hotel_request_details.*.amount' => 'nullable|numeric',
            'hotel_request_details.*.description' => 'nullable|string',
            'hotel_request_details.*.confirmed_note' => 'nullable|string',
            'hotel_request_details.*.requested_hotel_name' => 'required_with:hotel_request_details|string|in:Ayana Komodo Resort,Meruorah Hotel',
            'hotel_request_details.*.confirmed_price' => 'nullable|numeric',

            'assets' => 'nullable|array',
            'assets.*.title' => 'required_with:assets|string|max:255',
            'assets.*.description' => 'nullable|string',
            'assets.*.file' => 'required_with:assets|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'assets.*.is_external' => 'nullable|boolean',
        ];
    }
}
