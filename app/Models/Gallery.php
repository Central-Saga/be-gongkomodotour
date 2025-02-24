<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    /** @use HasFactory<\Database\Factories\GalleryFactory> */
    use HasFactory;

    protected $table = 'galleries';

    protected $fillable = [
        'title',
        'description',
        'category',
        'status',
    ];

    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }
}
