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

    /**
     * Menghitung biaya hotel berdasarkan harga per malam per pax.
     *
     * @param int $totalPax Jumlah penumpang
     * @param int $nights Jumlah malam
     * @return float
     */
    public function calculateHotelFee(int $totalPax, int $nights): float
    {
        return $this->price * $totalPax * $nights;
    }

    public function surcharges()
    {
        return $this->hasMany(Surcharge::class, 'hotel_occupancy_id', 'id');
    }
}
