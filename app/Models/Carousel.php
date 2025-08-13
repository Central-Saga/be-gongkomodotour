<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{
    /** @use HasFactory<\Database\Factories\CarouselFactory> */
    use HasFactory;

    protected $table = 'carousel';

    protected $fillable = [
        'title',
        'description',
        'order_num',
        'is_active',
    ];

    /**
     * Relasi ke assets (gambar carousel)
     * Gambar carousel sekarang dihandle oleh model Asset
     */
    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }

    /**
     * Get primary image dari assets
     */
    public function getPrimaryImageAttribute()
    {
        return $this->assets()->first();
    }
}
