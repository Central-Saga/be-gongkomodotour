<?php
// filepath: /c:/laragon/www/be-gongkomodotour/app/Models/Faq.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

        // Menentukan nama tabel yang digunakan
        protected $table = 'faq';
    /**
     * Atribut yang dapat diisi
     *
     * @var array
     */
    protected $fillable = [
        'question',
        'answer',
        'category',
        'display_order',
        'status',
    ];
}