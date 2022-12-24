<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hospital extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Filterable;
    
    protected $guarded = false;
    public $timestamps = false;

    public function rooms() {
        return $this->hasMany(HospitalRoom::class, 'hospital_id', 'id');
    }

    public function bookings() {
        return $this->hasMany(Booking::class);
    }

    public function today() {
        return $this->hasOne(Today::class, 'hospital_id', 'id');
    }
}
