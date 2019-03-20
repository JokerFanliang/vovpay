<?php

namespace App\Services\Pay;

use App\Models\Channel;
use App\Models\Channel_payment;
use App\Models\User;
use App\Models\User_rates;
use Illuminate\Http\Request;

interface PayInterface
{
    /**
     * @param User $user
     * @param Channel $channel
     * @param Channel_payment $Channel_payment
     * @param User_rates $user_rates
     * @param Request $request
     * @return mixed
     */
    public function pay(User $user, Channel $channel, Channel_payment $Channel_payment, User_rates $user_rates, Request $request);

    /**
     * 同步回调
     * @param Request $request
     * @return mixed
     */
    public function successCallback(Request $request);

    /**
     * 异步回调
     * @param Request $request
     * @return mixed
     */
    public function notifyCallback(Request $request);

    /**
     * 订单查询
     * @return mixed
     */
    public function queryOrder();
}