<?php

namespace App\Http\Pay\Controllers;

use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Redis;

class PayH5Controller extends Controller
{
    /**
     * 支付宝免签H5跳转页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View|void
     */
    public function h5pay(Request $request)
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if(strpos( $userAgent, 'AlipayClient' ) === false) return ;
        if(!$request->orderNo) return ;

        Redis::select(1);
        if(!Redis::exists($request->orderNo))
        {
            return json_encode('订单不存在或者已过期！',JSON_UNESCAPED_UNICODE);
        }

        $data = Redis::hGetAll($request->orderNo);
        $data['orderNo'] = $request->orderNo;

//        if($data['sweep_num'] >= 1){
//            return json_encode('二维码已使用，请重新发起支付！',JSON_UNESCAPED_UNICODE);
//        }
        $num = $data['sweep_num']+1;
        Redis::hset($request->orderNo, 'sweep_num',$num);

        $data['sweep_num'] = $num;
        if($data['type'] == 'alipay_packets'){
            return view('Pay.hbh5',compact('data'));
        }
    }

    /**
     * 检测手机系统
     * @return string
     */
    protected function get_device_type()
    {
        //全部变成小写字母
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $type = 'other';
        //分别进行判断
        if(strpos($agent, 'iphone') || strpos($agent, 'ipad'))
        {
            $type = 'ios';
        }

        if(strpos($agent, 'android'))
        {
            $type = 'android';
        }
        return $type;
    }
}
