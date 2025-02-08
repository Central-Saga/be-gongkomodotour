<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomersStoreRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:users,id',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'nasionality' => 'required|string|max:50',
            'region' => 'required|string|max:50',
            'status' => 'required|string|in:active,inactive',
        ];
    }
}
