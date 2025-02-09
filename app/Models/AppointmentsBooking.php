<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use function PHPUnit\Framework\returnSelf;

class AppointmentsBooking extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];
    public $timestamps = false;
    protected $casts = [
        'start_time' => 'datetime'
    ];
    protected $table = 'appointments_booking';
    protected $primaryKey = 'created';
    const upcoming = 0 ;
    const completed = 1 ;
    const canceled = 2 ;

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class , 'doctor_id');
    }

    public function getMethodAttribute()
    {
       if ($this->chat) {
            return "chat";
       }elseif ( $this->calling) {
             return "calling";
       }elseif ( $this->zoom){
             return "zoom";
       }
    }
    public function getFeesAttribute()
    {
        return $this->doctor?->personal?->fees;
    }

    public function speciality()
    {
        return $this->belongsTo(SpecialistsDepartment::class ,'booking_type','id');
    }

    public function getStatusAttribute()
    {
        switch( $this->booking_status){
            case($this::canceled):
                return "canceled";
            break;

            case($this::completed):
                return "completed";

            break;

            case($this::upcoming):
                return "upcoming";

            break;

            default:

            return "AN";
        }
    }
}
