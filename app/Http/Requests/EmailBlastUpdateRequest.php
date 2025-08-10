<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailBlastUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject'        => 'sometimes|required|string|max:255',
            'body'           => 'sometimes|required|string',
            // Update recipient_type validation to only allow valid enum values
            'recipient_type' => 'sometimes|required|string|in:all_customers,subscribers,spesific_list',
            'status'         => 'sometimes|required|string|in:Draft,Scheduled,Sent,Failed',
            'scheduled_at'   => 'nullable|date',
            'sent_at'        => 'nullable|date',

            // Validasi untuk recipients
            'recipients' => 'required_if:recipient_type,spesific_list|array',
            'recipients.*.id' => 'sometimes|required|integer|exists:email_blast_recipients,id',
            'recipients.*.email' => 'required_with:recipients|email|max:255',
            'recipients.*.status' => 'sometimes|required|string|in:Aktif,Non Aktif',
        ];
    }
}
