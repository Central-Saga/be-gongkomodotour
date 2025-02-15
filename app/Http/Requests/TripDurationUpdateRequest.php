<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TripDurationUpdateRequest extends FormRequest
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
            'trip_id' => 'sometimes|exists:trips,id',
            'duration_label' => 'sometimes|string|max:255',
            'duration_days' => 'sometimes|integer|min:1',
            'status' => 'sometimes|in:Aktif,Non Aktif',
        ];
    }
}
