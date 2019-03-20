<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/11/1
 * Time: 17:08
 */

namespace App\Repositories;

use App\Models\Statistical;

class StatisticalRepository
{

    protected $statistical;

    /**
     * @param Statistical $statistical
     */
    public function __construct(Statistical $statistical)
    {
        $this->statistical = $statistical;
    }

    /**
     * @param int $uid
     * @return mixed
     */
    public function findUserId(int $uid)
    {
        return $this->statistical->whereUserId($uid)->first();
    }

    /**
     * 用户余额增加
     * @param int $uid
     * @param float $amount
     * @return mixed
     */
    public function updateUseridBalanceIncrement(int $uid, float $amount)
    {
        return $this->statistical->whereUserId($uid)->increment('handlingFeeBalance',$amount);
    }

    /**
     * 用户充值余额增加
     * @param int $uid
     * @param float $amount
     * @return mixed
     */
    public function updateUseridHandlingFeeBalanceIncrement(int $uid, float $amount)
    {
        return $this->statistical->whereUserId($uid)->increment('handlingFeeBalance',$amount);
    }

    /**
     * 用户余额减少
     * @param int $uid
     * @param float $amount
     * @return mixed
     */
    public function updateUseridBalanceDecrement(int $uid, float $amount)
    {
        return $this->statistical->whereUserId($uid)->decrement('handlingFeeBalance',$amount);
    }

    /**
     * 用户充值余额减少
     * @param int $uid
     * @param float $amount
     * @return mixed
     */
    public function updateUseridHandlingFeeBalanceDecrement(int $uid, float $amount)
    {
        return $this->statistical->whereUserId($uid)->decrement('handlingFeeBalance',$amount);
    }
}