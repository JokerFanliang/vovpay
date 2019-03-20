<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id','created_at','updated_at'];

    /**
     * 一对多 订单表
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Order()
    {
        return $this->hasMany('App\Models\Order');
    }

    /**
     * 一对多 支付方式
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ChannelPayment()
    {
        return $this->hasMany('App\Models\Channel_payment');
    }
}
