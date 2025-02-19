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
            'boat_id' => 'required|exists:boat,id',
            'cabin_id' => 'required|exists:cabin,id',
            'user_id' => 'required|exists:users,id',
            'hotel_occupancy_id' => 'required|exists:hoteloccupancies,id',
            'total_pax' => 'required|integer',
            'status' => 'required|in:Pending,Confirmed,Cancelled',

            'booking_fees' => 'sometimes|array',
            'booking_fees.*.additional_fee_id' => 'required_with:booking_fees|exists:additional_fees,id',
        ];
    }
}
