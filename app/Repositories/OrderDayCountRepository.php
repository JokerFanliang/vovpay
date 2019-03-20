<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/11/1
 * Time: 17:08
 */

namespace App\Repositories;

use App\Models\Order_day_count;
use Illuminate\Support\Facades\DB;

class OrderDayCountRepository
{

    protected $order_day_count;

    /**
     * @param Order_day_count $order_day_count
     */
    public function __construct(Order_day_count $order_day_count)
    {
        $this->order_day_count = $order_day_count;
    }

    /**
     * 统计7天数据
     * @return mixed
     */
    public function getSevenDaysCount()
    {
        return DB::select("select date(`updated_at`) as tm, sys_amount,sys_income, sys_order_suc_count FROM pay_order_day_counts where date(`updated_at`) <= date(NOW()) GROUP BY date(`updated_at`)");
    }


    /**
     * 统计7天数据
     * @param int $uid
     * @return mixed
     */
    public function getOrderSevenDaysCount(array $query)
    {
        if (isset($query['user_id']) && $query['user_id']) {
            $where = " user_id = {$query['user_id']}";
        } elseif (isset($query['agent_id']) && $query['agent_id']) {
            $where = " agent_id = {$query['agent_id']}";
        }
        return DB::select("select date(`updated_at`) as tm, sys_amount,sys_income, sys_order_suc_count FROM pay_order_day_counts where date(`updated_at`) <= date(NOW()) and $where GROUP BY date(`updated_at`)");
    }

    /**
     * 统计7天数据
     * @param int $uid
     * @return mixed
     */
    public function getOrderUserSevenDaysCount(array $query)
    {
        if (isset($query['user_id']) && $query['user_id']) {
            $where = " user_id = {$query['user_id']}";
        } elseif (isset($query['agent_id']) && $query['agent_id']) {
            $where = " agent_id = {$query['agent_id']}";
        }
        return DB::select("select date(`updated_at`) as tm, merchant_amount,merchant_income, merchant_order_count FROM pay_order_day_counts where date(`updated_at`) <= date(NOW()) and $where GROUP BY date(`updated_at`)");
    }

    /**
     * 获取平台今日统计
     * @return mixed
     */
    public function findSysDayCount()
    {
        return $this->order_day_count->whereDate('updated_at', date('Y-m-d', time()))->first();
    }

    /**
     * 获取用户当日统计
     * @param int $uid
     * @return mixed
     */
    public function findDayAndUserCount(int $uid)
    {
        return $this->order_day_count->whereUserId($uid)->whereDate('updated_at', date('Y-m-d', time()))->first();
    }

}