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
    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_phone',
        'trip_id',
        'rating',
        'review',
        'is_approved',
        'is_highlight',
        'source',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_highlight' => 'boolean',
    ];

    /**
     * Relasi ke Trips.
     */
    public function trip()
    {
        return $this->belongsTo(Trips::class, 'trip_id');
    }

    /**
     * Scope untuk testimonial internal
     */
    public function scopeInternal($query)
    {
        return $query->where('source', 'internal');
    }

    /**
     * Scope untuk testimonial yang disetujui
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope untuk testimonial yang di-highlight
     */
    public function scopeHighlighted($query)
    {
        return $query->where('is_highlight', true);
    }
}
