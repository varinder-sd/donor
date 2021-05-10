<?php
  
namespace App\Models;
  
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
  
class User extends \TCG\Voyager\Models\User
{
    use HasFactory, Notifiable, HasApiTokens;
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
    ];

    protected $guarded = ['country','state','dob','district','city','gender'];  
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
  
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
   
   
    public function country()
    {
      
        return $this->hasOne(Country::class,'id','country')->select(array('id','name'));
    }  
    
    
    public function state()
    {
      
        return $this->hasOne(States::class,'id','state')->select(array('id','name'));
    }  
    
    public function city()
    {
      
        return $this->hasOne(Cities::class,'id','city')->select(array('id','name'));
    }  
    
    
    public function district()
    {
      
        return $this->hasOne(District::class,'id','district')->select(array('id','name'));
    }  

    
    public function donor()
    {
        return $this->hasMany('App\Models\Donor');
    }
    
    public function bulk()
    {
        return $this->hasMany('App\Models\Bulk');
    }
    
    public function patient()
    {
        return $this->hasMany('App\Models\Donor');
    }
}