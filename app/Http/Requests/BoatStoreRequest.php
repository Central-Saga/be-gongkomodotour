<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BoatStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'boat_name' => 'required|string|max:255',
            'spesification' => 'nullable|string',
            'cabin_information' => 'nullable|string',
            'facilities' => 'nullable|string',
            'status' => 'required|in:Aktif,Non Aktif',
            'cabins' => 'sometimes|array',
            'cabins.*.cabin_name' => 'required_with:cabins|string|max:255',
            'cabins.*.bed_type' => 'required_with:cabins|string|in:Single,Double,Queen,King,Single Bunk Bed,Double Bunk Bed',
            'cabins.*.min_pax' => 'required_with:cabins|integer|min:1',
            'cabins.*.max_pax' => 'required_with:cabins|integer|min:1|gte:cabins.*.min_pax',
            'cabins.*.base_price' => 'required_with:cabins|numeric|min:0',
            'cabins.*.additional_price' => 'nullable|numeric|min:0',
            'cabins.*.status' => 'required_with:cabins|in:Aktif,Non Aktif',
            // Validasi untuk files cabin (jika ada)
            'cabins.*.files' => 'sometimes|array',
            'cabins.*.files.*' => 'file|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'cabins.*.file_titles' => 'sometimes|array',
            'cabins.*.file_titles.*' => 'string|max:255',
            'cabins.*.file_descriptions' => 'sometimes|array',
            'cabins.*.file_descriptions.*' => 'nullable|string',
            // Validasi untuk assets boat
            'assets' => 'nullable|array',
            'assets.*.file' => 'required_with:assets|file|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'assets.*.title' => 'required_with:assets|string|max:255',
            'assets.*.description' => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            'cabins.*.files.*.mimes' => 'File cabin harus berupa gambar (jpeg, png, jpg, gif, webp)',
            'cabins.*.files.*.max' => 'Ukuran file cabin maksimal 10MB',
            'assets.*.file.mimes' => 'File asset boat harus berupa gambar (jpeg, png, jpg, gif, webp)',
            'assets.*.file.max' => 'Ukuran file asset boat maksimal 10MB',
        ];
    }
}
