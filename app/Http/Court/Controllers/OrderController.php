<?php

namespace App\Http\Court\Controllers;

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

    public function __construct( OrdersService $ordersService, ChannelService $channelService, ChannelPaymentsService $channelPaymentsService)
    {
        $this->ordersService  = $ordersService;
        $this->channelService = $channelService;
        $this->channelPaymentsService = $channelPaymentsService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = $request->input();

        if (count($query)) {
            $query['user_id'] = Auth::user()->id;
            $list = $this->ordersService->searchPage($query, 10);
            $orderInfoSum = $this->ordersService->orderInfoSum($query);
        } else {
            $query['user_id'] = Auth::user()->id;
            $list = $this->ordersService->getAllPage($query,10);
            $orderInfoSum = $this->ordersService->orderInfoSum($query);
        }


        $chanel_list = $this->channelService->getAll();
        $payments_list = $this->channelPaymentsService->getAll();

        return view('Court.Order.order', compact('list', 'query', 'chanel_list', 'payments_list', 'orderInfoSum'));

    }


    /**
     * 详情
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $rule =$this->ordersService->findId($id);
        return ajaxSuccess('获取成功',$rule);
    }

    /**
     * 状态变更
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveStatus(Request $request)
    {
        $data['status'] = $request->status;
        $result = $this->ordersService->updateStatus($request->id, $data);

        if($result)
        {
            return ajaxSuccess('修改成功！');
        }else{
            return ajaxError('修改失败！');
        }
    }
}
