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

    const STATUS_DRAFT = 'Draft';
    const STATUS_SCHEDULED = 'Scheduled';
    const STATUS_PROCESSING = 'Processing';
    const STATUS_SENT = 'Sent';
    const STATUS_FAILED = 'Failed';

    protected $attributes = [
        'status' => self::STATUS_DRAFT,
    ];

    public function recipients()
    {
        return $this->hasMany(EmailBlastRecipient::class);
    }
}
