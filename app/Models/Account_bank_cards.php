<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/1/15
 * Time: 17:31
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Account_bank_cards extends Model
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
     * 一对多反向 支付方式
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ChannelPayment()
    {
        return $this->belongsTo('App\Models\Channel_payment');
    }

}