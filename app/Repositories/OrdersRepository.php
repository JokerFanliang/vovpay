<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/11/1
 * Time: 17:08
 */

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class OrdersRepository
{

    protected $order;

    /**
     * UsersRepository constructor.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * 获取所有，分页
     * @param array $data
     * @param int $page
     * @return mixed
     */
    public function getAllPage(array $data, int $page)
    {
        $sql = " 1=1 ";
        $where = [];

        if (isset($data['agent_id']) && $data['agent_id'] != '-1') {
            $sql .= ' and agent_id = ?';
            $where['agent_id'] = $data['agent_id'];
        }

        if (isset($data['user_id']) && $data['user_id'] != '-1') {
            $sql .= ' and user_id = ?';
            $where['user_id'] = $data['user_id'];
        }

        return $this->order->whereRaw($sql, $where)->orderBy('id', 'desc')->paginate($page);
    }

    /**
     * 订单统计
     * @param array $data
     * @param string $type 默认只统计成功订单
     * @return array
     */
    public function Summing(array $data, string $type ='success')
    {
        $where = [];
        if($type == 'success')
        {
            $sql = " status=1 ";
        }else{
            $sql = " 1=1 ";
        }

        if (isset($data['merchant']) && $data['merchant']) {
            $sql .= 'and merchant = ?';
            $where['merchant'] = $data['merchant'];
        }

        if (isset($data['orderNo']) && $data['orderNo']) {
            $sql .= ' and orderNo = ?';
            $where['orderNo'] = $data['orderNo'];
        }

        if (isset($data['underOrderNo']) && $data['underOrderNo']) {
            $sql .= ' and underOrderNo = ?';
            $where['underOrderNo'] = $data['underOrderNo'];
        }

        if (isset($data['status']) && $data['status'] != '-1') {
            $sql .= ' and status = ?';
            $where['status'] = $data['status'];
        }

        if (isset($data['channel_id']) && $data['channel_id'] != '-1') {
            $sql .= ' and channel_id = ?';
            $where['channel_id'] = $data['channel_id'];
        }

        if (isset($data['agent_id']) && $data['agent_id'] != '-1') {
            $sql .= ' and agent_id = ?';
            $where['agent_id'] = $data['agent_id'];
        }

        if (isset($data['user_id']) && $data['user_id'] != '-1') {
            $sql .= ' and user_id = ?';
            $where['user_id'] = $data['user_id'];
        }

        if (isset($data['phone_uid']) && $data['phone_uid']) {
            $sql .= ' and phone_uid = ?';
            $where['phone_uid'] = $data['phone_uid'];
        }

        if (isset($data['channel_payment_id']) && $data['channel_payment_id'] != '-1') {
            $sql .= ' and channel_payment_id = ?';
            $where['channel_payment_id'] = $data['channel_payment_id'];
        }

        if (isset($data['orderTime']) && $data['orderTime']) {
            $time = explode(" - ", $data['orderTime']);
            $sql .= ' and updated_at <= ?';
            $where['updated_at'] = $time[1];
        }else{
            $today = Carbon:: today()->toDateTimeString();
            $sql .= ' and updated_at >= ?';
            $where['updated_at1'] = $today;
            $now = Carbon::now()->toDateTimeString();
            $sql .= ' and updated_at <= ?';
            $where['updated_at2'] = $now;
        }

        return $this->order->whereRaw($sql, $where)
            ->select(DB::raw('sum(amount) as amountSum ,count(id) as orderCount,sum(orderRate) as orderRateSum, sum(agentAmount) as agentSum,sum(userAmount) as userSum'))
            ->get()->toArray();
    }

    /**
     * 添加
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->order->create($data);
    }

    /**
     * 查询单条
     * @param int $id
     * @return mixed
     */
    public function findId(int $id)
    {
        return $this->order->whereId($id)->first();
    }

    /**
     * 根据平台订单查询
     * @param string $order_no
     * @return mixed
     */
    public function findOrderNo(string $order_no)
    {
        return $this->order->whereOrderno($order_no)->first();
    }

    /**
     * 根据商户订单查询
     * @param string $order_no
     * @return mixed
     */
    public function findUnderOrderNo(string $order_no)
    {
        return $this->order->whereunderorderno($order_no)->first();
    }

    /**
     * 修改
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data)
    {
        return $this->order->whereId($id)->update($data);
    }

    /**
     * 删除
     * @param int $id
     * @return mixed
     */
    public function del(int $id)
    {
        return $this->order->whereId($id)->delete();
    }

    /**获取搜索订单
     * @param $sql
     * @param $where
     * @param int $page
     * @return mixed
     */
    public function searchPage($sql,$where,int $page)
    {

        return $this->order->whereRaw($sql, $where)->orderBy('id', 'desc')->paginate($page);
    }

   /**获取搜索订单统计数据
     * @param $sql
     * @param $where
     * @param $page
     * @return mixed
     */
    public function searchOrderInfoSum($sql, $where, $page)
    {
        return $this->order->whereRaw($sql, $where)
            ->select(DB::raw('sum(amount) as amountSum ,count(id) as orderCount,sum(orderRate) as orderRateSum, sum(agentAmount) as agentSum,sum(userAmount) as userSum'))
            ->get()->toArray();

    }

    /**
     * 获取订单成功率
     * @param $sql
     * @param $where
     */
    public function orderSuccessRate($sql, $where){
        return $this->order->whereRaw($sql,$where)->groupBy('status')
            ->select(DB::raw('status, count(id) as `count`'))->get()->toArray();
    }

}