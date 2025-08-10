<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailBlastRecipient extends Model
{
    use HasFactory;

    protected $table = 'email_blast_recipient';

    protected $fillable = [
        'email_blast_id',
        'recipient_email',
        'status',
    ];

    public function emailBlast()
    {
        return $this->belongsTo(EmailBlast::class);
    }
}
