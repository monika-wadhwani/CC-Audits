<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    public $table="role_user";
    public $timestamps=false;

    public function getUser()
    {
        return $this->hasOne('App\User','id','user_id');
    }
}
