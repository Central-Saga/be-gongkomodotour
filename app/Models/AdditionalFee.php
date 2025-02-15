<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalFee extends Model
{
    /** @use HasFactory<\Database\Factories\AdditionalFeeFactory> */
    use HasFactory;

    protected $table = 'additional_fees';

    protected $fillable = [
        'trip_id',
        'fee_category',
        'price',
        'region',
        'unit',
        'pax_min',
        'pax_max',
        'day_type',
        'status',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
