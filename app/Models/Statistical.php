<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Statistical extends Model
{
    protected $guarded = ['id','created_at','updated_at'];

    /**
     * 一对一反向关联用户。
     */
    public function User()
    {
        return $this->belongsTo('App\Models\User');
    }
}
