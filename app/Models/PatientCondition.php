<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientCondition extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Filterable;
    
    protected $guarded = false;
    public $timestamps = true;
}
