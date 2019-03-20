<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/27
 * Time: 10:54
 */

namespace App\Http\Agent\Controllers;


use App\Services\OrdersService;
use App\Services\UserRateService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\OrderDayCountService;
use App\Services\StatisticalService;
use App\Services\UserService;

class IndexController extends Controller
{
    protected $ordersService;
    protected $userService;
    protected $orderDayCountService;
    protected $statisticalService;
    protected $userRateService;

    /**
     * IndexController constructor.
     * @param UserRateService $userRateService
     * @param OrdersService $ordersService
     * @param OrderDayCountService $orderDayCountService
     * @param StatisticalService $statisticalService
     */
    public function __construct(UserRateService $userRateService ,OrdersService $ordersService, UserService $userService, OrderDayCountService $orderDayCountService, StatisticalService $statisticalService)
    {
        $this->ordersService = $ordersService;
        $this->orderDayCountService = $orderDayCountService;
        $this->statisticalService = $statisticalService;
        $this->userRateService    = $userRateService;
        $this->userService = $userService;
    }


    /**
     * 后台主页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request)
    {
        $title='主页';
        $query = $request->input();
        $query['user_id'] = Auth::user()->id;

        $user_day_count   = $this->orderDayCountService->findDayAndUserCount($query['user_id']);
        $order_day_count = json_encode(convert_arr_key($this->orderDayCountService->getOrderUserSevenDaysCount($query), 'tm'));
        return view('Agent.Index.index', compact('title','user_day_count', 'order_day_count'));
    }


    /**
     * 商户费率
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function rate()
    {
        return view('Agent.Index.memberRate');
    }

    /**
     * api管理
     */
    public function api()
    {
        $title='API管理';
        $uid = Auth::user()->id;
        $list = $this->userRateService->channelAll($uid);
        return view('Agent.Index.api',compact('title','list'));
    }

     /**
     * 修改支付密码
     * @param string $password    原始密码
     * @param string $newpassword 新密码
     */
    public function editpaypwd(Request $request){
        $data = $request->input();
        $id = Auth::user()->id;
        $result = $this->userService->editPayPassword($id, $data);
        if($result){
            return ajaxSuccess('修改成功');
        } else {
            return ajaxError('原始支付密码错误,修改失败');
        }
    }

    //修改密码
    public function editPassword(Request $request)
    {
        $data = $request->input();
        $id = Auth::user()->id;
        $result = $this->userService->updatePassword($id, $data);

        if ($result) {
            return ajaxSuccess('密码修改成功！');
        } else {
            return ajaxError('原密码错误，修改失败！');
        }
    }
}