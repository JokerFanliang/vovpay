<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name'];

    /**
     * 获得此角色所属的管理员。
     */
    public function admins()
    {
        return $this->belongsToMany('App\Models\Admin','admin_role');
    }

    /**
     * 获得此角色所属的菜单。
     */
    public function rules()
    {
        return $this->belongsToMany('App\Models\Rule');
    }
}
