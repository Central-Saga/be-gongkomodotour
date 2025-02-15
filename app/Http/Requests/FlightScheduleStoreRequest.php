<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FlightScheduleStoreRequest extends FormRequest
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
            'route' => 'required|string|max:255',
            'eta_time' => 'required|date',
            'eta_text' => 'required|string|max:255',
            'etd_time' => 'required|date',
            'etd_text' => 'required|string|max:255',
        ];
    }
}
