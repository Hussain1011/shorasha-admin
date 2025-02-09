<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accent extends Model
{
    use HasFactory;


    protected $fillable = ['name', 'language_id'];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
