<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCollectRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
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

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
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
