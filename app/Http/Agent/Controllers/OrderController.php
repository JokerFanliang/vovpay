<?php

namespace App\Http\Agent\Controllers;

use App\Services\ChannelPaymentsService;
use App\Services\ChannelService;
use App\Services\DownNotifyContentService;
use App\Services\OrdersService;
use App\Services\StatisticalService;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Services\QuotalogService;
use App\Jobs\SendOrderAsyncNotify;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    protected $ordersService;
    protected $channelService;
    protected $channelPaymentsService;
    protected $downNotifyContentService;
    protected $statisticalService;
    protected $userService;

    public function __construct(UserService $userService, StatisticalService $statisticalService, DownNotifyContentService $downNotifyContentService, OrdersService $ordersService, ChannelService $channelService, ChannelPaymentsService $channelPaymentsService)
    {
        $this->ordersService = $ordersService;
        $this->channelService = $channelService;
        $this->channelPaymentsService = $channelPaymentsService;
        $this->downNotifyContentService = $downNotifyContentService;
        $this->statisticalService = $statisticalService;
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $title = '交易管理';
        $uid = Auth::user()->id;
        $query = $request->input();
        $query['agent_id'] = $uid;

        $search = $this->ordersService->searchPage($query, 10);
        $list = $search['list'];
        $orderInfoSum = $search['info'];


        $chanel_list = $this->channelService->getAll();
        $payments_list = $this->channelPaymentsService->getAll();

        unset($query['_token']);
        unset($query['agent_id']);

        return view('Agent.Order.order', compact('title', 'list', 'query', 'chanel_list', 'payments_list', 'orderInfoSum'));

    }


    /**
     * 详情
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $rule = $this->ordersService->findId($id);
        if ($rule['agent_id'] == Auth::user()->id && !empty($rule)) {
            return ajaxSuccess('获取成功', $rule);
        } else {
            return ajaxError('获取失败');
        }


    }


    /**
     * 状态变更
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveStatus(Request $request)
    {
        $uid = Auth::user()->id;
        // 只能是总管理能修改
        if (!isset($request->agent_id) || $uid != $request->agent_id) {
            return ajaxError('修改失败！');
        }

        $order = $this->ordersService->findId($request->id, 'collection');
        if (!$order) return ajaxError('修改失败！');
        if ($order->status != 0) return ajaxError('修改失败！');
        $data['status'] = 1;
        $result = $this->ordersService->updateStatus($request->id, $data);
        if (!$result) return ajaxError('修改失败！');

        // 获取挂号方式
        $add_account_type = env('ADD_ACCOUNT_TYPE');
        // 商户收益增加
        if ($add_account_type != 1) {
            $this->statisticalService->updateUseridHandlingFeeBalanceIncrement($order->user_id, $order->userAmount);
        }
        // 代理收益增加
        if ($order->agentAmount > 0) {
            $this->statisticalService->updateUseridHandlingFeeBalanceIncrement($order->agent_id, $order->agentAmount);
        }

        if ($add_account_type == 4) {
            $this->userService->userAddOrReduceQuota($order->phone_uid, $order->amount, 1);
            $quotalogService = app(QuotalogService::class);
            $quota_array = [
                'user_id' => $order->phone_uid,
                'quota' => $order->amount,
                'quota_type' => 1,
                'action_type' => 1,
            ];
            $quotalogService->add($quota_array);
        }
        SendOrderAsyncNotify::dispatch($order)->onQueue('orderNotify');
        return ajaxSuccess('修改成功！');

    }

    /**
     * 订单补发通知
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reissue(Request $request)
    {
        if (empty($request->agent_id) || $request->agent_id != Auth::user()->id) {
            return ajaxError('非法操作');
        }

        $order = $this->ordersService->findId($request->id, 'collection');

        if (!$order || $order->status != 1) {
            return ajaxError('订单不存在或未支付');
        }

        if ($this->downNotifyContentService->send($order)) {
            return ajaxSuccess('已发送');
        } else {
            return ajaxError('发送失败');
        }
    }
}
