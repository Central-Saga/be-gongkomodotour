<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HotelOccupanciesUpdateRequest extends FormRequest
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
            'hotel_name' => 'sometimes|required|string|max:255',
            'hotel_type' => 'sometimes|required|string|max:50',
            'occupancy' => 'sometimes|required|integer',
            'price' => 'sometimes|required|numeric',
            'status' => 'sometimes|required|string|in:Aktif, Non Aktif',
        ];
    }
}