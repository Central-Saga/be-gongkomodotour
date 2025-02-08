<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TripStoreRequest extends FormRequest
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
            'name'          => 'required|string|max:255',
            'include'       => 'required|string',
            'exclude'       => 'required|string',
            'note'          => 'nullable|string',
            'duration'      => 'required|integer',
            'start_time'    => 'required|date',
            'end_time'      => 'required|date|after_or_equal:start_time',
            'meeting_point' => 'required|string',
            'type'          => 'required|string',
            'status'        => 'required|in:Aktif,Non Aktif',
        ];
    }
}
