<?php

namespace App\Services;

use App\Repositories\StatisticalRepository;

class StatisticalService
{
    protected $statisticalRepository;

    public function __construct(StatisticalRepository $statisticalRepository)
    {
        $this->statisticalRepository = $statisticalRepository;
    }

    /**
     * 根据id获取
     * @param int $uid
     * @return array
     */
    public function findUserId(int $uid)
    {
        return $this->statisticalRepository->findUserId($uid);
    }

    /**
     * 用户余额增加
     * @param int $uid
     * @param float $amount
     * @return mixed
     */
    public function updateUseridBalanceIncrement(int $uid, float $amount)
    {
        return $this->statisticalRepository->updateUseridBalanceIncrement($uid,$amount);
    }

    /**
     * 用户充值余额增加
     * @param int $uid
     * @param float $amount
     * @return mixed
     */
    public function updateUseridHandlingFeeBalanceIncrement(int $uid, float $amount)
    {
        return $this->statisticalRepository->updateUseridHandlingFeeBalanceIncrement($uid,$amount);
    }

    /**
     * 用户余额减少
     * @param int $uid
     * @param float $amount
     * @return mixed
     */
    public function updateUseridBalanceDecrement(int $uid, float $amount)
    {
        return $this->statisticalRepository->updateUseridBalanceDecrement($uid,$amount);
    }

    /**
     * 用户充值余额减少
     * @param int $uid
     * @param float $amount
     * @return mixed
     */
    public function updateUseridHandlingFeeBalanceDecrement(int $uid, float $amount)
    {
        return $this->statisticalRepository->updateUseridHandlingFeeBalanceDecrement($uid,$amount);
    }
}