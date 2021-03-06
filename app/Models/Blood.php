<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blood extends Model
{
    use HasFactory;
    protected $table = 'blood_group';
    
    public function donor()
    {
        return $this->hasMany('App\Models\Donor');
    }
    
    public function patient()
    {
        return $this->hasMany('App\Models\Donor');
    }
}
