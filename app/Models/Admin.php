<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id','created_at','updated_at'];

    protected $hidden = [
        'remember_token',
    ];

    /**
     * 获得此管理员的角色。
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role','admin_role');
    }


}
