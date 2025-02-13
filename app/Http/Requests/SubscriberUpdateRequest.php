<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriberUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'customer_id' => 'required|integer|exists:customers,id',
            'email'       => 'required|email|max:255|unique:subscriber,email',
            'name'        => 'required|string|max:255',
            'status'      => 'required|in:Aktif,Non Aktif',
        ];
    }
}