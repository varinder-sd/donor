<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    use HasFactory;
    
    public function donor()
    {
        return $this->hasMany('App\Models\Donor');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class,'city','id');
    }
}
