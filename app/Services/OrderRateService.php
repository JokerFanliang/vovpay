<?php
namespace App\Services;

use App\Models\Channel_payment;
use App\Models\User;
use App\Models\User_rates;
use App\Repositories\UserRateRepository;

class OrderRateService{
    protected $userRateRepository;
    protected $userRate;   // 商户实际费率
    protected $runRate;    // 运营费率
    protected $costRate;   // 成本费率
    protected $agentAmount = 0;// 代理收入
    protected $orderFee    = 0; // 订单手续费
    protected $amount;   // 订单金额

    public function __construct(UserRateRepository $userRateRepository)
    {
        $this->userRateRepository = $userRateRepository;
    }

    /**
     * 订单费用计算
     * @param User $user
     * @param Channel_payment $Channel_payment
     * @param User_rates $user_rates
     * @param float $amount
     * @return array
     */
    public function orderFee(User $user, Channel_payment $Channel_payment, User_rates $user_rates, float $amount)
    {
        $user_rate      = $user_rates->rate;
        $this->runRate  = $Channel_payment->runRate;
        $this->costRate = $Channel_payment->costRate;
        $this->amount   = $amount;
        $this->orderFee = $this->orderPoundage($user_rate);
        if($user->parentId != 0)
        {
            $this->agentAmount = $this->agentOrderPoundage($user->parentId, $Channel_payment->id);
        }
        $sysAmount = $this->sysOrderPoundage();
        $data = array(
            'orderFee'    => $this->orderFee,
            'userAmount'  => bcsub($amount,$this->orderFee,2),
            'agentAmount' => $this->agentAmount,
            'sysAmount'   => $sysAmount,
        );
        return $data;
    }

    /**
     * 订单手续费计算
     * @param float $user_rate
     * @return float
     */
    protected function orderPoundage(float $user_rate)
    {
        // 商户费率等于0，走运营费率；商户费率大于运营费率，走运营费率; 小于成本费率，走成本费率
        if($user_rate == 0 || $user_rate > $this->runRate || $user_rate < $this->costRate)
        {
            $this->userRate = $this->runRate;
        }else{
            $this->userRate = $user_rate;
        }
        return bcmul($this->userRate,$this->amount,2);
    }

    /**
     * 代理收益，只计算一级代理
     * @param int $agent_id
     * @param int $pay_id
     * @return float
     */
    protected function agentOrderPoundage(int $agent_id, int $pay_id)
    {
        $user_rate = $this->userRateRepository->findUseridAndPaymentid($agent_id, $pay_id);
        if(!$user_rate)
        {
             return 0;
        }
        $agent_rate_differ = bcsub($this->userRate,$user_rate->rate,6);
        if( $agent_rate_differ <= 0 )
        {
            return 0;
        }
        return bcmul($agent_rate_differ,$this->amount,2);
    }

    /**
     * 系统收益
     * @return float
     */
    protected function sysOrderPoundage()
    {
        // 成本
        $cost_amount = bcmul($this->costRate,$this->amount,2);
        // 手续费-成本-代理 = 系统收益
        return bcsub(bcsub($this->orderFee, $cost_amount,2), $this->agentAmount,2);
    }

}