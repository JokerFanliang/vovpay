<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
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
     * 一对多反向 通道表
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Channel()
    {
        return $this->belongsTo('App\Models\Channel');
    }

    /**
     * 一对多反向 支付方式表
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ChannelPayment()
    {
        return $this->belongsTo('App\Models\Channel_payment');
    }
}
