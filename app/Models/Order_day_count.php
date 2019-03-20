<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_day_count extends Model
{
    //

    /**
     * 一对多反向关联用户表
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function User()
    {
        return $this->belongsTo('App\Models\User');
    }
}
