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
        'link',
        'order_num',
        'is_active',
    ];

    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }
}
