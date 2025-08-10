<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CabinStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'boat_id' => 'required|exists:boat,id',
            'cabin_name' => 'required|string|max:255',
            'bed_type' => 'required|string|in:Single,Double,Queen,King',
            'min_pax' => 'required|integer|min:1',
            'max_pax' => 'required|integer|min:1',
            'base_price' => 'required|numeric|min:0',
            'additional_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:Aktif,Non Aktif',
        ];
    }
}
