<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HospitalRoom extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $guarded = false;
    public $timestamps = false;

    public function hospital() {
        return $this->belongsTo(Hospital::class, 'hospital_id', 'id');
        // return $this->belongsTo(Hospital::class);
    }
}
