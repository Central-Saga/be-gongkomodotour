<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailBlastRecipientUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email_blast_id'  => 'sometimes|required|integer|exists:email_blast,id',
            'recipient_email' => 'sometimes|required|email|max:255',
            'status'          => 'sometimes|required|string|in:pending,sent,failed',
        ];
    }
}