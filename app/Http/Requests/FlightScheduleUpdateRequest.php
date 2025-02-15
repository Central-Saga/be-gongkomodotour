<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FlightScheduleUpdateRequest extends FormRequest
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
            'route' => 'sometimes|required|string|max:255',
            'eta_time' => 'sometimes|required|date',
            'eta_text' => 'sometimes|required|string|max:255',
            'etd_time' => 'sometimes|required|date',
            'etd_text' => 'sometimes|required|string|max:255',
        ];
    }
}
