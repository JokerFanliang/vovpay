<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id','created_at','updated_at'];

    /**
     * 这个属性应该被转换为原生类型.
     *
     * @var array
     */
    protected $casts = [
        'extend' => 'array',
    ];

    /**
     * 一对多反向 用户表
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function User()
    {
        return $this->belongsTo('App\Models\User');
    }


    /**
     * 获取结算状态。
     *
     * @return string
     */
    public function getStatusAttribute($value)
    {
        switch ($value){
            case 0:
                return '未处理';
            case 1:
                return '处理中';
            case 2:
                return '已结算';
            case 3:
                return '结算异常';
            case 4:
                return '已取消';
            default:
                return '错误状态';
        }

    }

    /**
     * 获取结算处理时间。
     *
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
       return $value==$this->created_at?'---':$value;
    }

    /**
     * 获取商户订单号。
     *
     * @return string
     */
    public function getOutOrderIdAttribute($value)
    {
        return $value?:'---';
    }


}
