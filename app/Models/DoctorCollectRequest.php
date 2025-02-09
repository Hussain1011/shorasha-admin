<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorCollectRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'doctor_id',
        'amount',
        'status',
        'collected',
        'reference_no',
        'created_by',
        'updated_by',
    ];
    const Pending = 0;
    const Approved = 1;
    const Rejected = 2;

    public function doctor(){
        return $this->belongsTo(User::class,'doctor_id','id');
    }

    public function getStatustAttribute()
    {
        switch($this->status){
            case($this::Pending):
                return "Pending";
            break;

            case($this::Approved):
                return "Approved";

            break;

            case($this::Rejected):
                return "Rejected";

            break;

            default:

            return "AN";
        }
    }
}
