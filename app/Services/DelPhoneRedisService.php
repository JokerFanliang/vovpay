<?php

/**
 * 删除账号redis信息
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/1/29
 * Time: 14:21
 */

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class DelPhoneRedisService {

    public function del(string $phone_id, string $account_type)
    {
        Redis::select(1);
        switch ($account_type){
            case '云闪付':
                Redis::del($phone_id.'cloudpay');
                break;
            case '微信':
                Redis::del($phone_id.'wechat');
                break;
            case '银行卡':
                Redis::del($phone_id.'bankmsg');
                break;
            case '支付宝':
                Redis::del($phone_id.'alipay');
                break;
        }
        return ;
    }

}