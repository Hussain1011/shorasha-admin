<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    const USER = 1 ;
    const DOCTOR = 2 ;
    const ADMIN = 3 ;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function personal()
    {
       return $this->hasOne(UserPersonal::class , 'user_id');
    }

    public function review()
    {
       return $this->hasMany(UsersReview::class , 'user_id');
    }

    public function user_booking()
    {
        return $this->hasMany(AppointmentsBooking::class , 'user_id');
    }
    public function doctor_booking()
    {
        return $this->hasMany(AppointmentsBooking::class , 'doctor_id');
    }

    public function specialists()
    {
       return $this->belongsToMany(Specialist::class , 'doctor_specialists' , 'doctor_id' , 'specialist_id');
    }



}
