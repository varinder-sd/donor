<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Bulk extends Model
{
    protected $table = 'donors_import';
       use HasFactory;
    
    protected $guarded = ['donor_picture','created_by']; 
    protected $fillable = [
        'user_id','name', 'email','	covid_recovered_warrior','blood_group','covid_postive_date','city_id','pin_code','alternate_mobile_number','created_by','can_donate'
    ];
    
    public function user()
    {
        return $this->belongsTo('App\Models\User')->select(array('id','name', 'email'));
    }
}