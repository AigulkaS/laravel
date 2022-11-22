<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory;
    // use SoftDeletes;
    
    protected $guarded = false;
    public $timestamps = false;

    public function role() {
        return $this->belongsToMany(Role::class);
    }
}
