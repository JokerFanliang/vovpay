<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quota_log extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id','created_at','updated_at'];

    /**
     * 数组中的属性会被隐藏
     *
     * @var array
     */
    protected $hidden = ['created_at','updated_at'];

    /**
     * 一对一反向关联用户。
     */
    public function User()
    {
        return $this->belongsTo('App\Models\User');
    }
}
