<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailBlastStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'subject'        => 'required|string|max:255',
            'body'           => 'required|string',
            // Update recipient_type validation to only allow valid enum values
            'recipient_type' => 'required|string|in:all_customers,subscribers,spesific_list',
            'status'         => 'sometimes|required|string|in:draft,scheduled,sent,failed',
            'scheduled_at'   => 'nullable|date',
            'sent_at'        => 'nullable|date',
        ];
    }
}