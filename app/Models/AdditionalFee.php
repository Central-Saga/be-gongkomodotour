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
        'is_required',
        'status',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Menghitung fee tambahan berdasarkan unit.
     *
     * @param int $totalPax Jumlah penumpang
     * @param int $tripDurationDays Durasi trip (dalam hari)
     * @return float
     */
    public function calculateFee($totalPax, $tripDurationDays)
    {
        switch ($this->unit) {
            case 'per_pax':
                return $this->price * $totalPax;
            case 'per_5pax':
                $groupCount = ceil($totalPax / 5);
                return $this->price * $groupCount;
            case 'per_day':
                return $this->price * $tripDurationDays;
            case 'per_day_guide':
                return $this->price * $tripDurationDays;
            default:
                return 0;
        }
    }
}
