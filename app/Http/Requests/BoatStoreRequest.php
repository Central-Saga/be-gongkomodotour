<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BoatStoreRequest extends FormRequest {
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'boat_name' => 'required|string|max:255',
            'spesification' => 'nullable|string',
            'cabin_information' => 'nullable|string',
            'facilities' => 'nullable|string',
            'status' => 'required|in:Aktif,Non Aktif',
        ];
    }
}
