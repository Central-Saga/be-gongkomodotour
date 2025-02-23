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
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'total_amount' => 'required|numeric',
            'payment_status' => 'required|in:Menunggu Pembayaran,Lunas,Ditolak',

            'hotel_request_details' => 'nullable|array',
            'hotel_request_details.*.amount' => 'required_with:hotel_request_details|numeric',
            'hotel_request_details.*.description' => 'nullable|string',
            'hotel_request_details.*.confirmed_note' => 'nullable|string',
            'hotel_request_details.*.requested_hotel_name' => 'nullable|string',
            'hotel_request_details.*.confirmed_price' => 'nullable|numeric',
        ];
    }
}
