<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelRequest extends Model
{
    /** @use HasFactory<\Database\Factories\HotelRequestFactory> */
    use HasFactory;

    protected $table = 'hotel_requests';

    protected $fillable = [
        'transaction_id',
        'user_id',
        'confirmed_note',
        'requested_hotel_name',
        'request_status',
        'confirmed_price',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function detailTransactions()
    {
        return $this->morphMany(DetailTransaction::class, 'reference');
    }
}
