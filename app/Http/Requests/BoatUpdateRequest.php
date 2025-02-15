<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BoatUpdateRequest extends FormRequest {
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'boat_name' => 'sometimes|required|string|max:255',
            'spesification' => 'nullable|string',
            'cabin_information' => 'nullable|string',
            'facilities' => 'nullable|string',
            'status' => 'sometimes|required|in:Aktif,Non Aktif',
        ];
    }
}
