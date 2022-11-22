<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalRoom extends Model
{
    use HasFactory;
    
    protected $guarded = false;
    public $timestamps = false;

    public function hospital() {
        return $this->belongsTo(Hospital::class);
    }
}
