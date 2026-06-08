<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Kolom yang boleh diisi secara massal (melindungi dari input ilegal)
    protected $fillable = [
        'name',
        'identifier_number',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // RELASI: Satu User bisa mempunyai banyak reservasi
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}