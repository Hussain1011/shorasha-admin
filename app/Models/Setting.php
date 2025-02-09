<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    const APP_COMMISSION = "app_commition";
    protected $fillable = [
        'key',
        'value',
    ];
}
