<?php

namespace App\Http\User\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Services\ChannelPaymentsService;
use App\Services\ChannelService;
use App\Services\OrdersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class OrderController extends Controller
{
    protected $ordersService;
    protected $channelService;
    protected $channelPaymentsService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OrdersService $ordersService, ChannelService $channelService, ChannelPaymentsService $channelPaymentsService)
    {
        $this->ordersService = $ordersService;
        $this->channelService = $channelService;
        $this->channelPaymentsService = $channelPaymentsService;
    }

    /**
     * Show the application dashboard.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $uid = Auth::user()->id;
        $query = $request->input();
        $query['user_id'] = $uid;

        $search = $this->ordersService->searchPage($query, 10);
        $list = $search['list'];
        $orderInfoSum = $search['info'];


        $chanel_list = $this->channelService->getAll();
        $payments_list = $this->channelPaymentsService->getAll();

        unset($query['_token']);
        unset($query['user_id']);

        return view('User.Order.order', compact('list', 'query', 'chanel_list', 'payments_list', 'orderInfoSum'));
    }

    /**
     * 详情
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $query['id'] = $id;
        $rule = $this->ordersService->findId($id);
        if (isset($rule) && $rule['user_id']==Auth::user()->id) {
            return ajaxSuccess('获取成功', $rule);
        } else {
            return ajaxError('获取失败');
        }

    }

    public function recharge()
    {
        return view('User.Recharge.recharge');
    }

    public function invoice()
    {
        return view('User.invoice');
    }
}
