<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surcharge extends Model
{
    /** @use HasFactory<\Database\Factories\SurchargeFactory> */
    use HasFactory;

    protected $table = 'surcharges';

    protected $fillable = [
        'trip_id',
        'season',
        'start_date',
        'end_date',
        'surcharge_price',
        'status',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function detailTransactions()
    {
        return $this->morphMany(DetailTransaction::class, 'reference');
    }
}
