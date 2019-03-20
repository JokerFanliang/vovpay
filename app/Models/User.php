<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id','created_at','updated_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token','PaymentPassword'
    ];

    /**
     * 一对多 银行卡
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function BankCard()
    {
        return $this->hasMany('App\Models\Bank_card');
    }

    /**
     * 一对多 订单按天统计表
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OrderDayCount()
    {
        return $this->hasMany('App\Models\Order_day_count');
    }

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
     * 多对多 用户表
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ChannelPayment()
    {
        return $this->belongsToMany('App\Models\Channel_payment', 'User_rates','user_id','channel_payment_id')
            ->withPivot('channel_id', 'rate', 'status');
    }

    /**
     * 一对多 提现表
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Withdraw()
    {
        return $this->hasMany('App\Models\Withdraw');
    }

    /**
     * 一对一关联商户定时统计表。
     */
    public function Statistical()
    {
        return $this->hasOne('App\Models\Statistical');
    }

    /**
     * 一对一关联场外商户上分记录表。
     */
    public function QuotaLog()
    {
        return $this->hasOne('App\Models\Quota_log');
    }
}
