<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id','created_at','updated_at'];

    /**
     * 获得此菜单所属的角色。
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role');
    }
}
