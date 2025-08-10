<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    /** @use HasFactory<\Database\Factories\AssetFactory> */
    use HasFactory;

    protected $table = 'assets';

    protected $fillable = [
        'assetable_id',
        'assetable_type',
        'title',
        'description',
        'file_path',
        'file_url',
        'is_external',
    ];

    public function assetable()
    {
        return $this->morphTo();
    }

    // Relasi ke carousel
    public function carousel()
    {
        return $this->morphTo();
    }
}
