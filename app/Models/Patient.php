<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    
    public function user()
    {
        return $this->belongsTo('App\Models\User')->select(array('id','name', 'email'));
    }
    
    public function blood()
    {
        return $this->belongsTo(Blood::class,'blood_group_id','id')->select(array('id', 'name'));;
    }
}
