<?php

namespace App\Services;


use App\Models\Order;
use App\Tool\Md5Verify;
use Illuminate\Support\Facades\Log;

class DownNotifyContentService{

    /**
     * @param Order $orders
     * @return bool
     */
    public function send(Order $orders)
    {

        $userService = app(UserService::class);
        $user = $userService->findId($orders->user_id);

        $paraBuild = $this->paraBuild($orders);
        $md5Verify = app(Md5Verify::class);

        $paraBuild['sign'] = $md5Verify->getSign($paraBuild, $user->apiKey);

        $result = sendCurl($orders->notifyUrl,$paraBuild);

        if(strtolower($result) == 'success'){
            return true;
        }else{
            Log::info('orderAsyncNotify:', ['content' => json_encode($paraBuild),'callback'=>$result]);
            return false;
        }
    }

    /**
     * 组装需要返回给下游的数据
     * @param Order $orders
     * @return array
     */
    protected function paraBuild(Order $orders)
    {
        $extend = json_decode($orders->extend,true);
        $param = array(
            'merchant'      => $orders->merchant,
            'amount'        => $orders->amount,
            'sys_order_no'  => $orders->orderNo,
            'out_order_no'  => $orders->underOrderNo,
            'order_time'    => $extend['tm'],
            'attach'        => $extend['attach'],
            'cuid'          => $extend['cuid'],
            'realPrice'     => $extend['realPrice'],
        );

        return $param;
    }


}