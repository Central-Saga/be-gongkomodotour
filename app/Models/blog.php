<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Traits\HasSlug;

class Blog extends Model
{
    use HasFactory, HasSlug;

    protected $table = 'blog';

    protected $fillable = [
        'author_id',
        'title',
        'category',
        'content',
        'status',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }
}
