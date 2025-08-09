<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarouselUpdateRequest extends FormRequest
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
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'link'        => 'nullable|url|max:255',
            'order_num'   => 'sometimes|required|integer|min:0',
            'is_active'   => 'sometimes|required|boolean',
            'asset_ids'   => 'nullable|array',
            'asset_ids.*' => 'exists:assets,id',
        ];
    }
}
