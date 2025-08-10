<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'booking_id',
        'bank_type',
        'total_amount',
        'payment_status',
    ];

    protected $casts = [
        'bank_type' => 'string',
        'total_amount' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(DetailTransaction::class, 'transaction_id', 'id');
    }

    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }
}
