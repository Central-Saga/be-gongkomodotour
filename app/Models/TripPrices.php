<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripPrices extends Model
{
    /** @use HasFactory<\Database\Factories\TripPricesFactory> */
    use HasFactory;

    public const PRICE_TYPE_FIXED = 'fixed';
    public const PRICE_TYPE_BY_REQUEST = 'by_request';

    protected $table = 'trip_prices';

    protected $fillable = [
        'trip_duration_id',
        'pax_min',
        'pax_max',
        'price_per_pax_nullable',
        'price_type',
        'status',
        'region',
    ];

    protected $casts = [
        'price_per_pax_nullable' => 'decimal:2',
    ];

    /**
     * API/read: expose price as price_per_pax (from price_per_pax_nullable, fallback to legacy price_per_pax).
     */
    public function getPricePerPaxAttribute(): ?float
    {
        $val = $this->attributes['price_per_pax_nullable'] ?? null;
        if ($val !== null) {
            return (float) $val;
        }
        $legacy = $this->attributes['price_per_pax'] ?? null;
        return $legacy !== null ? (float) $legacy : null;
    }

    public function tripDuration()
    {
        return $this->belongsTo(TripDuration::class, 'trip_duration_id', 'id');
    }
}
