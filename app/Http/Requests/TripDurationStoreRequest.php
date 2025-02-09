<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TripDurationStoreRequest extends FormRequest
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
            'duration_label' => 'required|string|max:255',
            'duration_days' => 'required|integer|min:1',
            'status' => 'required|in:Aktif,Non Aktif',
        ];
    }
}
