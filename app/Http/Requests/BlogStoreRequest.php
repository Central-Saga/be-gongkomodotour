<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'author_id' => 'required|integer|exists:users,id',
            'title'     => 'required|string|max:255',
            'category'  => 'required|in:travel,tips',
            'content'   => 'required|string',
            'status'    => 'required|in:draft,published',
        ];
    }
}
