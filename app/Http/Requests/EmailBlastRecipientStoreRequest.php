<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailBlastRecipientStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'email_blast_id'  => 'required|integer|exists:email_blast,id',
            'recipient_email' => 'required|email|max:255',
            'status'          => 'sometimes|string|in:pending,sent,failed',
        ];
    }
}