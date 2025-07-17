<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bureau extends Model
{
    protected $table = 'bureaux';
    
    protected $fillable = ['nom','emplacement'];

    public function reservations() {
        return $this->hasMany(Reservation::class);
    }
}
