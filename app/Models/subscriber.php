<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer; // Pastikan model ini sudah ada

class Subscriber extends Model
{
    use HasFactory;

    protected $table = 'subscriber';

    protected $fillable = [
        'customer_id',
        'email',
        'name',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }
}
