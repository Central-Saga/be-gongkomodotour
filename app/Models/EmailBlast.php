<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailBlast extends Model
{
    use HasFactory;

    protected $table = 'email_blast';

    protected $fillable = [
        'subject',
        'body',
        'recipient_type',
        'status',
        'scheduled_at',
        'sent_at'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function recipients()
    {
        return $this->hasMany(EmailBlastRecipient::class);
    }
}
