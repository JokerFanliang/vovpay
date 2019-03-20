<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/11/1
 * Time: 17:08
 */

namespace App\Repositories;
use App\Models\User_rates;

class UserRateRepository
{

    protected $user_rates;

    /**
     * UsersRepository constructor.
     * @param User_rates $user_rates
     */
    public function __construct( User_rates $user_rates)
    {
        $this->user_rates = $user_rates;
    }

    /**
     * 根据用户id获取指定字段
     * @param int $uid
     * @return mixed
     */
    public function findUserId( int $uid)
    {
        return $this->user_rates->whereUserId($uid)
            ->select('channel_payment_id', 'rate', 'status', 'channel_id')
            ->get();
    }


    public function findUseridAndPaymentid(int $uid, int $paymentid){
        return $this->user_rates->whereUserId($uid)
            ->whereChannelPaymentId($paymentid)
            ->select('id','channel_payment_id', 'rate', 'status', 'channel_id')
            ->first();
    }

    public function findUseridAndPaymentidAndStatus(int $uid, int $paymentid){
        return $this->user_rates->whereUserId($uid)
            ->whereChannelPaymentId($paymentid)
            ->whereStatus(1)
            ->select('id','channel_payment_id', 'rate', 'status', 'channel_id')
            ->first();
    }

    public function add(array $data)
    {
        return $this->user_rates->create($data);
    }
    /**
     * 获取商户通道
     */
    public function channelAll(String $userid,int $status){
        return $this->user_rates->whereUserId($userid)->whereStatus($status)->get();
    }
}