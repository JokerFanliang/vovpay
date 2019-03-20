<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel_payment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id','created_at','updated_at'];

    /**
     * 一对多 云端固码配置
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function AccountCloud()
    {
        return $this->hasMany('App\Models\Account_clouds');
    }

    /**
     * 一对多 任意码
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function AccountPhone()
    {
        return $this->hasMany('App\Models\Account_phone');
    }

    /**
     * 一对多 订单表
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Order()
    {
        return $this->hasMany('App\Models\Order');
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
     * 多对多 用户表
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function User()
    {
        return $this->belongsToMany('App\Models\User', 'User_rates','user_id','channel_payment_id')
            ->withPivot('channel_id', 'rate', 'status');
    }
}
