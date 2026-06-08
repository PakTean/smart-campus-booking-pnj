<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'is_available',
    ];

    // RELASI: Satu fasilitas bisa memiliki banyak catatan reservasi
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}