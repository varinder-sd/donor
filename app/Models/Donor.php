<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory;
    
    protected $guarded = ['*']; 
    
    
    public function user()
    {
        return $this->belongsTo('App\Models\User')->select(array('id','name', 'email'));
    }
    
    public function blood()
    {
        return $this->belongsTo(Blood::class,'blood_group_id','id')->select(array('id', 'name'));;
    }
    
    public function country()
    {
        return $this->belongsTo('App\Models\Country')->select(array('id', 'name'));
    }
    
    public function state()
    {
        return $this->belongsTo('App\Models\States')->select(array('id', 'name'));
    }
    
    public function city()
    {
        return $this->belongsTo('App\Models\Cities')->select(array('id', 'name'));
    }
    public function district()
    {
        return $this->belongsTo('App\Models\District')->select(array('id', 'name'));
    }
}
