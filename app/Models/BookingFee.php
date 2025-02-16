<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingFee extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFeeFactory> */
    use HasFactory;

    protected $table = 'booking_fees';

    protected $fillable = [
        'booking_id',
        'additional_fee_id',
        'fee_type',
        'total_price',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function additionalFee()
    {
        return $this->belongsTo(AdditionalFee::class);
    }
}
