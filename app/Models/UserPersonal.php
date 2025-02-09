<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPersonal extends Model
{
    use HasFactory;
    protected $table = 'user_personal';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    public function nationality()
    {
       return $this->belongsTo(Nationality::class , 'nationality_id');
    }
}
