<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingStoreRequest extends FormRequest
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
            'trip_id' => 'required|exists:trips,id',
            'trip_duration_id' => 'required|exists:trip_durations,id',
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'required|exists:users,id',
            'hotel_occupancy_id' => 'required|exists:hoteloccupancies,id',
            'total_pax' => 'required|integer',
            'status' => 'required|in:Pending,Confirmed,Cancelled',
            'start_date' => 'required|date',
            'end_date' => 'required|date',

            'cabin_ids' => 'sometimes|array',
            'cabin_ids.*' => 'integer|exists:cabin,id',
            'boat_ids' => 'sometimes|array',
            'boat_ids.*' => 'integer|exists:boat,id',

            'additional_fee_ids' => 'sometimes|array',
            'additional_fee_ids.*.additional_fee_id' => 'required_with:additional_fee_ids|exists:additional_fees,id',
        ];
    }
}
