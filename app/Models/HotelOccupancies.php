<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelOccupancies extends Model
{
    use HasFactory;

    // Jika nama tabel tidak sesuai dengan konvensi, aktifkan baris berikut:

    protected $table = 'hoteloccupancies';

    // Field yang dapat diisi melalui mass assignment
    protected $fillable = [
        'hotel_name',
        'hotel_type',
        'occupancy',
        'price',
        'status'
    ];
}