<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['user_id','bureau_id','date_debut','date_fin'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function bureau() {
        return $this->belongsTo(Bureau::class);
    }
}
