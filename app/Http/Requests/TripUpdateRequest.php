<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TripUpdateRequest extends FormRequest
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
            'name'          => 'sometimes|required|string|max:255',
            'include'       => 'sometimes|required|string',
            'exclude'       => 'sometimes|required|string',
            'note'          => 'sometimes|nullable|string',
            'duration'      => 'sometimes|required|integer',
            'start_time'    => 'sometimes|required|date',
            'end_time'      => 'sometimes|required|date|after_or_equal:start_time',
            'meeting_point' => 'sometimes|required|string',
            'type'          => 'sometimes|required|string',
            'status'        => 'sometimes|required|in:Aktif,Non Aktif',
        ];
    }
}
