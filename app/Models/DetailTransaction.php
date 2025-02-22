<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaction extends Model
{
    /** @use HasFactory<\Database\Factories\DetailTransactionFactory> */
    use HasFactory;

    protected $table = 'detail_transactions';

    protected $fillable = [
        'transaction_id',
        'type',
        'amount',
        'description',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }

    public function reference()
    {
        return $this->morphTo();
    }
}
