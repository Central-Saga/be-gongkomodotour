<?php
// filepath: /c:/laragon/www/be-gongkomodotour/app/Models/Testimonial.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    // Menentukan nama tabel yang digunakan
    protected $table = 'testimonial';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array
     */
    // filepath: /c:/laragon/www/be-gongkomodotour/app/Models/Testimonial.php
    protected $fillable = [
        'customer_id',
        'trip_id',
        'rating',
        'review',
        'is_approved',
        'is_highlight',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_highlight' => 'boolean',
    ];

    /**
     * Relasi ke Customers.
     */
    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }

    /**
     * Relasi ke Trips.
     */
    public function trip()
    {
        return $this->belongsTo(Trips::class, 'trip_id');
    }
}
