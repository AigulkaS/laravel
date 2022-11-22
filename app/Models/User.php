<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $guarded = false;
    public $timestamps = false;

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function hospital() {
        return $this->belongsTo(Hospital::class);
    }

    public function surgeonBookings() {
        return $this->hasMany(Booking::class, 'surgeon_id', 'id');
    }

    public function cardiologistBookings() {
        return $this->hasMany(Booking::class, 'cardiologist_id', 'id');
    }
}
