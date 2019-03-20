<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account_clouds extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id','created_at','updated_at'];

    /**
     * 一对多反向 用户表
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function User()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * 一对多 固码列表
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function AccountCloudSolid()
    {
        return $this->hasMany('App\Models\Account_cloud_solid');
    }

    /**
     * 一对多反向 支付方式
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ChannelPayment()
    {
        return $this->belongsTo('App\Models\Channel_payment');
    }
}
