<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
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
    protected $hidden = ['status','created_at','updated_at'];

}
