<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\VerifyEmailNotification;
use App\Notifications\ResetPasswordNotification;
use NotificationChannels\WebPush\HasPushSubscriptions;

//class User extends Authenticatable implements MustVerifyEmail
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;
    use Filterable;
    use HasPushSubscriptions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
//    protected $fillable = [
//        'name',
//        'email',
//        'password',
//    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
//    protected $hidden = [
//        'password',
//        'remember_token',
//    ];

    public $timestamps = false;

    protected $guarded = false;
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification());
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function hasRole($roleName) {
        $role = $this->role()->get();
        if ($role->name == $roleName) {
            return true;
        }
        return false;
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
