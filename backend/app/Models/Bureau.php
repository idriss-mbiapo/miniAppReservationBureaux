<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bureau extends Model
{
    /** @use HasFactory<\Database\Factories\BureauFactory> */
    use HasFactory;

    protected $table = 'bureaux';

    protected $fillable = ['nom','emplacement'];

    public function reservations() {
        return $this->hasMany(Reservation::class);
    }
}
