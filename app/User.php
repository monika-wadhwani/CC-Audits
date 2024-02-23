<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Scopes\UsersDeletedScope;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable,EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id','name', 'email', 'password','mobile','qa_target','is_first_time_user'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $guarded = ['_token'];
    // protected static function boot()
    // {
    //     parent::boot();

    
    //     static::addGlobalScope(new UsersDeletedScope);
  

    // }
    public function is_email_verified()
    {
        return ($this->email_verified_at)?1:0;
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }
    // user masrers
    // process==1
    // region==2
    // language==3
    public function my_processes()
    {
        return $this->hasMany('App\UsersMaster','user_id','id')->where('master_type',1);
    }

    public function my_regions()
    {
        return $this->hasMany('App\UsersMaster','user_id','id')->where('master_type',2);
    }

    public function my_languages()
    {
        return $this->hasMany('App\UsersMaster','user_id','id')->where('master_type',3);
    }

    public function client_detail()
    {
        return $this->hasOne('App\ClientAdmin');
    }
    public function partner_admin_detail()
    {
        return $this->hasOne('App\Partner','admin_user_id');
    }
    public function spoc_detail()
    {
        return $this->hasOne('App\PartnersProcessSpoc');   
    }

    public function token()
    {
        return $this->hasOne('App\Token');
    }

    public function get_qtl_name()
    {
        return $this->belongsTo('App\User','reporting_user_id','id');

    }
    


}
