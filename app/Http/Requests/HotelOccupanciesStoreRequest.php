<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HotelOccupanciesStoreRequest extends FormRequest
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
            'hotel_name' => 'required|string|max:255',
            'hotel_type' => 'required|string|max:50',
            'occupancy' => 'required|string|in:Single Occupancy,Double Occupancy',
            'price' => 'required|numeric',
            'status' => 'required|string|in:Aktif,Non Aktif',
            'surcharges' => 'sometimes|array',
            'surcharges.*.season' => 'required_with:surcharges|string',
            'surcharges.*.start_date' => 'required_with:surcharges|date',
            'surcharges.*.end_date' => 'required_with:surcharges|date|after_or_equal:surcharges.*.start_date',
            'surcharges.*.surcharge_price' => 'required_with:surcharges|numeric',
            'surcharges.*.status' => 'required_with:surcharges|string|in:Aktif,Non Aktif',
        ];
    }
}
