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
            'boat_id' => 'sometimes|required|exists:boats,id',
            'cabin_id' => 'sometimes|required|exists:cabins,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'hotel_occupancy_id' => 'sometimes|required|exists:hotel_occupancies,id',
            'total_price' => 'sometimes|required|numeric',
            'total_pax' => 'sometimes|required|integer',
            'status' => 'sometimes|required|in:Pending,Confirmed,Cancelled',

            'booking_fees' => 'sometimes|array',
            'booking_fees.*.additional_fee_id' => 'required_with:booking_fees|exists:additional_fees,id',
            'booking_fees.*.fee_type' => 'required_with:booking_fees|in:Open Trip,Private Trip',
            'booking_fees.*.total_price' => 'required_with:booking_fees|numeric',
        ];
    }
}
