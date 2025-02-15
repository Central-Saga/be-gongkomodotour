<?php 

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CabinUpdateRequest extends FormRequest {
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'boat_id' => 'sometimes|required|exists:boat,id',
            'cabin_name' => 'sometimes|required|string|max:255',
            'bed_type' => 'sometimes|required|string|in:single,double,queen,king',
            'min_pax' => 'sometimes|required|integer|min:1',
            'max_pax' => 'sometimes|required|integer|min:1',
            'base_price' => 'sometimes|required|numeric|min:0',
            'additional_price' => 'nullable|numeric|min:0',
            'status' => 'sometimes|required|in:available,booked',
        ];
    }
}
