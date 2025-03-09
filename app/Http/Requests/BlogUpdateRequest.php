<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogUpdateRequest extends FormRequest
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
            'slug'      => 'required|string|max:255|unique:blog,slug',
            'content'   => 'required|string',
            'status'    => 'required|in:draft,published',
        ];
    }
}