<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BoatUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'boat_name' => 'sometimes|required|string|max:255',
            'spesification' => 'nullable|string',
            'cabin_information' => 'nullable|string',
            'facilities' => 'nullable|string',
            'status' => 'sometimes|required|in:Aktif,Non Aktif',
            'cabins' => 'sometimes|array',
            'cabins.*.id' => 'nullable|exists:cabin,id',
            'cabins.*.cabin_name' => 'required_with:cabins|string|max:255',
            'cabins.*.bed_type' => 'required_with:cabins|string',
            'cabins.*.min_pax' => 'required_with:cabins|integer|min:1',
            'cabins.*.max_pax' => 'required_with:cabins|integer|min:1|gte:cabins.*.min_pax',
            'cabins.*.base_price' => 'required_with:cabins|numeric|min:0',
            'cabins.*.additional_price' => 'nullable|numeric|min:0',
            'cabins.*.status' => 'required_with:cabins|in:Aktif,Non Aktif',
            'assets' => 'sometimes|array',
            'assets.*.id' => 'nullable|exists:assets,id',
            'assets.*.file' => 'sometimes|required_with:assets|file|mimes:jpeg,png,jpg,gif|max:2048',
            'assets.*.type' => 'required_with:assets|string',
            'assets.*.description' => 'nullable|string'
        ];
    }
}
