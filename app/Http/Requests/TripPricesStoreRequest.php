<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TripPricesStoreRequest extends FormRequest
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
            'trip_duration_id' => 'required|exists:trip_durations,id',
            'pax_min' => 'required|integer|min:1',
            'pax_max' => 'required|integer|min:1',
            'price_per_pax' => 'required|numeric|min:0',
            'status' => 'required|in:Aktif,Non Aktif',
        ];
    }
}
