<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Today extends Model
{
    use HasFactory;
    use Filterable;
    
    protected $guarded = false;
    public $timestamps = false;

    public function hospital() {
        return $this->belongsTo(Hospital::class);
    }

    public function surgeon() {
        return $this->belongsTo(User::class, 'surgeon_id', 'id');
    }

    public function cardiologist() {
        return $this->belongsTo(User::class, 'cardiologist_id', 'id');
    }
}
