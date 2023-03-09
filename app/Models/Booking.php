<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Filterable;
    
    protected $guarded = false;
    public $timestamps = true;

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function room() {
        return $this->belongsTo(HospitalRoom::class, 'room_id', 'id');
    }

    public function hospital() {
        return $this->belongsTo(Hospital::class, 'hospital_id', 'id');
    }

    public function disease() {
        return $this->belongsTo(Disease::class, 'disease_id', 'id');
    }

    public function condition() {
        return $this->belongsTo(PatientCondition::class, 'condition_id', 'id');
    }
}
