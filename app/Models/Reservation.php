<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'facility_id',
        'start_time',
        'end_time',
        'purpose',
        'status',
        'rejection_note',
    ];

    // RELASI: Satu data reservasi ini dimiliki oleh/merujuk ke satu User tertentu
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // RELASI: Satu data reservasi ini merujuk ke satu Fasilitas tertentu
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}