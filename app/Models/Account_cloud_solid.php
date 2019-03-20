<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account_cloud_solid extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id','created_at','updated_at'];

    /**
     * 一对多反向 固码配置表
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function AccountCloud()
    {
        return $this->belongsTo('App\models\Account_clouds');
    }
}
