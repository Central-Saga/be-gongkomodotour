<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingUpdateRequest extends FormRequest
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
            'trip_id' => 'sometimes|required|exists:trips,id',
            'trip_duration_id' => 'sometimes|required|exists:trip_durations,id',
            'customer_id' => 'sometimes|required|exists:customers,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'hotel_occupancy_id' => 'sometimes|required|exists:hoteloccupancies,id',
            'total_pax' => 'sometimes|required|integer',
            'status' => 'sometimes|required|in:Pending,Confirmed,Cancelled',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date',

            'cabins' => 'sometimes|array',
            'cabins.*.cabin_id' => 'required|exists:cabin,id',
            'cabins.*.total_pax' => 'required|integer|min:1',

            'boat_ids' => 'sometimes|array',
            'boat_ids.*' => 'integer|exists:boat,id',

            'additional_fee_ids' => 'sometimes|array',
            'additional_fee_ids.*.additional_fee_id' => 'required_with:additional_fee_ids|exists:additional_fees,id',
        ];
    }
}
