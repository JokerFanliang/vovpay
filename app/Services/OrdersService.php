<?php

namespace App\Services;

use App\Models\User;
use App\Models\Channel;
use App\Models\Channel_payment;
use App\Models\User_rates;
use App\Repositories\OrdersRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrdersService
{
    protected $ordersRepository;
    protected $orderRateService;

    public function __construct(OrdersRepository $ordersRepository, OrderRateService $orderRateService)
    {
        $this->ordersRepository = $ordersRepository;
        $this->orderRateService = $orderRateService;
    }

    /**
     * @param User $user
     * @param Channel $channel
     * @param Channel_payment $Channel_payment
     * @param Request $request
     * @param User_rates $user_rates
     * @param array $account_array
     * @return mixed
     */
    public function add(User $user, Channel $channel, Channel_payment $Channel_payment, Request $request, User_rates $user_rates, array $account_array)
    {
        if( $request->pay_code == "alipay_bank" )
        {
            $amount = $account_array['realPrice'];
        }else if($request->pay_code == "alipay_bank2" ||$request->pay_code == "bank_solidcode"){
            $amount = $account_array['realPrice'];
        }else{
            $amount = $request->amount;
        }
        // 订单收益计算
        $order_amount_array = $this->orderRateService->orderFee($user, $Channel_payment, $user_rates, $amount);
        $extend = array(
            'tm'        => $request->order_time ? $request->order_time : date('Y-m-d H:i:s',time()),
            'attach'    => $request->attach ? $request->attach : '',
            'cuid'      => $request->cuid ? $request->cuid : '',
            'realPrice' => $request->amount,
        );

        $param = array(
            'user_id'       => $user->id,
            'merchant'      => $user->merchant,
            'agent_id'      => $user->parentId,
            'channel_id'    => $channel->id,
            'channelName'   => $channel->channelName,
            'channel_payment_id'    => $Channel_payment->id,
            'paymentName'           => $Channel_payment->paymentName,
            'account'       => $account_array['account'] ?: $account_array['account'],
            'phone_id'      => $account_array['phone_id'],
            'phone_uid'     => $account_array['phone_uid'],
            'orderNo'       => getOrderId(),
            'underOrderNo'  => $request->order_no,
            'amount'        => $amount,
            'orderRate'     => $order_amount_array['orderFee'],
            'sysAmount'     => $order_amount_array['sysAmount'],
            'agentAmount'   => $order_amount_array['agentAmount'],
            'userAmount'    => $order_amount_array['userAmount'],
            'notifyUrl'     => $request->notify_url,
            'successUrl'    => $request->return_url,
            'extend'        => json_encode($extend),
            'status'        => 0
        );
        return $this->ordersRepository->add($param);
    }

    /**
     * 订单详情统计
     * @param array $data
     * @param string $type 默认只统计成功订单
     * @return array
     */
    public function orderInfoSum(array $data, string $type ='success')
    {
        return $this->ordersRepository->Summing($data,$type);
    }

    /**
     * 根据平台订单查询
     * @param string $order_no
     * @return mixed
     */
    public function findOrderNo(string $order_no)
    {
        return $this->ordersRepository->findOrderNo($order_no);
    }

    /**
     * 根据商户订单查询
     * @param string $order_no
     * @return mixed
     */
    public function findUnderOrderNo(string $order_no)
    {
        return $this->ordersRepository->findUnderOrderNo($order_no);
    }

    /**
     * 订单查询，带分页
     * @param array $data
     * @param int $page
     * @return mixed
     */
    public function searchPage(array $data, int $page)
    {
        $sql = ' 1=1 ';
        $where = [];

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

        if (isset($data['channel_payment_id']) && $data['channel_payment_id'] != '-1') {
            $sql .= ' and channel_payment_id = ?';
            $where['channel_payment_id'] = $data['channel_payment_id'];
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
        if(isset($data['account']) && $data['account']){
            $sql .= ' and account = ?';
            $where['account'] = $data['account'];
        }

        if (isset($data['orderTime']) && $data['orderTime']) {
            $time = explode(" - ", $data['orderTime']);
            $sql .= ' and updated_at >= ?';
            $where['updated_at1'] = $time[0];
            $sql .= ' and updated_at <= ?';
            $where['updated_at2'] = $time[1];

        }else{
            $today = Carbon:: today()->toDateTimeString();
            $sql .= ' and updated_at >= ?';
            $where['updated_at1'] = $today;
            $now = Carbon::now()->toDateTimeString();
            $sql .= ' and updated_at <= ?';
            $where['updated_at2'] = $now;
        }
        $serach['list'] = $this->ordersRepository->searchPage($sql,$where, $page);
        $serach['info'] = $this->ordersRepository->searchOrderInfoSum($sql, $where, $page);
        //根据条件计算成功率
        $successrates = $this->ordersRepository->orderSuccessRate($sql,$where);
        
        $temp = 0;
        if(count($successrates) > 1){
            $count = array_sum(array_pluck($successrates,'count'));
            $temp  = last($successrates)['count'] / $count * 100;
        }
        if(count($successrates) == 1){
            $status = array_pluck($successrates,'status');
           
            $temp = $status[0] == 1 ? 100 : 0;
        }
        $serach['successrate'] = sprintf('%.0f',$temp).'%';
        return $serach;

    }


    /**
     * 根据id获取
     * @param int $id
     * @param string $type
     * @return mixed | array
     */
    public function findId(int $id, $type ='array')
    {
        $orders = $this->ordersRepository->findId($id);
        if($type == 'array')
        {
            return $orders->toArray();
        }else{
            return $orders;
        }

    }

    /**
     * 获取所有，分页
     * @param array $data
     * @param int $page
     * @return mixed
     */
    public function getAllPage(array $data,int $page)
    {
        return $this->ordersRepository->getAllPage($data,$page);
    }

    /**
     * 更新
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data)
    {
        return $this->ordersRepository->update($id, $data);
    }

    /**
     * 状态变更
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateStatus(int $id, array $data){

        return $this->ordersRepository->update($id, $data);
    }

    /**
     * 伪删除
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        return $this->ordersRepository->update($id,['status'=>2]);
    }
}