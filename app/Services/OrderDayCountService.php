<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/17
 * Time: 13:54
 */

namespace App\Services;


use App\Repositories\OrderDayCountRepository;

class OrderDayCountService
{
    protected $orderDayCountRepository;

    public function __construct(OrderDayCountRepository $orderDayCountRepository)
    {
        $this->orderDayCountRepository = $orderDayCountRepository;
    }

    /**
     * 统计7天数据
     * @return mixed
     */
    public function getSevenDaysCount()
    {
        return $this->orderDayCountRepository->getSevenDaysCount();
    }

    /**
     * 统计7天数据
     * @return mixed
     */
    public function getOrderSevenDaysCount($query)
    {
        return $this->orderDayCountRepository->getOrderSevenDaysCount($query);
    }

    /**
     * 统计7天数据
     * @return mixed
     */
    public function getOrderUserSevenDaysCount($query)
    {
        return $this->orderDayCountRepository->getOrderUserSevenDaysCount($query);
    }

    /**
     * 平台今日数据
     * @return mixed
     */
    public function findSysDayCount()
    {
        return $this->orderDayCountRepository->findSysDayCount();
    }

    /**
     * 平台今日数据
     * @return mixed
     */
    public function findDayAndUserCount(int $uid)
    {
        return $this->orderDayCountRepository->findDayAndUserCount($uid);
    }

}