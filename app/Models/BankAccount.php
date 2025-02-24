<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    /** @use HasFactory<\Database\Factories\BankAccountFactory> */
    use HasFactory;

    protected $table = 'bank_accounts';

    protected $fillable = [
        'bank_name',
        'account_name',
        'account_number',
        'swift_code',
        'status',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'bank_account_id', 'id');
    }
}
