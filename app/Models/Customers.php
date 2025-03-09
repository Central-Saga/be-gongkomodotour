<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    /** @use HasFactory<\Database\Factories\CustomersFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'alamat',
        'no_hp',
        'nasionality',
        'region',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
