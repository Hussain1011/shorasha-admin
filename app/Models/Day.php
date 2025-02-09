<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;
    public function doctor()
    {
        return $this->belongsToMany(Doctor::class,'doctor_schedule','day_index','doctor_id');
    }

    public function slots()
    {
       return $this->hasMany(DoctorSchedule::class,'day_index','day_index');
    }
}
