<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Collection;
use App\Common\RespCode;

class ChooseAccountService{

    protected $accountPhoneService;
    protected $accountBankCardsService;
    protected $price;
    protected $pay_code;

    public function __construct(AccountPhoneService $accountPhoneService, AccountBankCardsService $accountBankCardsService)
    {
        $this->accountPhoneService = $accountPhoneService;
        $this->accountBankCardsService = $accountBankCardsService;
    }

    /**
     * 随机获取账号
     * @param User $user
     * @param string $type
     * @param float $price
     * @return mixed|void
     */
    public function getAccount(User $user, string $type, float $price)
    {
        $valid_account = [];
        $this->price    = $price;
        $this->pay_code = $type;
        // 获取挂号方式配置,确定选号
        $add_account_type  =  env('ADD_ACCOUNT_TYPE');

        // 根据挂号方式获取所有开启的账号:1商户后台挂号,2总后台挂号,3代理后台挂号,4三方挂号
        if( $add_account_type == 1 )
        {
            if( $this->pay_code == 'alipay_packets' || $this->pay_code == 'alipay' || $this->pay_code == 'wechat' || $this->pay_code == 'cloudpay')
            {
                $account_list = $this->accountPhoneService->getStatusAndAccountType($type,$user->id,1);
            }else if($this->pay_code == 'alipay_bank'){
                $account_list = $this->accountBankCardsService->getStatusAndUserIdAndBanKMark($user->id,1,'ANTBANK');
            }else if($this->pay_code == 'alipay_bank2' || $this->pay_code == 'bank_solidcode'){
                $account_list = $this->accountBankCardsService->getStatusAndAccountTypeAndUserIdAndNotBanKMark($user->id,1,'ANTBANK',$type);
            } else if($this->pay_code == 'alipay_solidcode'||$this->pay_code == 'wechat_solidcode'||$this->pay_code == 'cloudpay_solidcode'){
                $account_list = $this->accountPhoneService->getStatusAndAccountTypeAndSolidcode($type,$user->id,1);
            }

        }else if( $add_account_type == 2 ){

            if( $this->pay_code == 'alipay_packets' || $this->pay_code == 'alipay' || $this->pay_code == 'wechat' || $this->pay_code == 'cloudpay')
            {
                $account_list = $this->accountPhoneService->getStatusAndAccountType($type,100000,1);
            }else if($this->pay_code == 'alipay_bank'){
                $account_list = $this->accountBankCardsService->getStatusAndUserIdAndBanKMark(100000,1,'ANTBANK');
            }else if($this->pay_code == 'alipay_bank2' || $this->pay_code == 'bank_solidcode'){
                $account_list = $this->accountBankCardsService->getStatusAndAccountTypeAndUserIdAndNotBanKMark(100000,1,'ANTBANK',$type);
            } else if($this->pay_code == 'alipay_solidcode'||$this->pay_code == 'wechat_solidcode'||$this->pay_code == 'cloudpay_solidcode'){
                $account_list = $this->accountPhoneService->getStatusAndAccountTypeAndSolidcode($type,100000,1);
            }
        }else if( $add_account_type == 3 ){

            if( $this->pay_code == 'alipay_packets' || $this->pay_code == 'alipay' || $this->pay_code == 'wechat' || $this->pay_code == 'cloudpay' ) {

                $account_list = $this->accountPhoneService->getStatusAndAccountType($type, $user->parentId, 1);
            }else if($this->pay_code == 'alipay_bank'){
                $account_list = $this->accountBankCardsService->getStatusAndUserIdAndBanKMark($user->parentId,1,'ANTBANK');
            }else if($this->pay_code == 'alipay_bank2' || $this->pay_code == 'bank_solidcode'){
                $account_list = $this->accountBankCardsService->getStatusAndAccountTypeAndUserIdAndNotBanKMark($user->parentId,1,'ANTBANK',$type);
            } else if($this->pay_code == 'alipay_solidcode'||$this->pay_code == 'wechat_solidcode'||$this->pay_code == 'cloudpay_solidcode'){
                $account_list = $this->accountPhoneService->getStatusAndAccountTypeAndSolidcode($type,$user->parentId,1);
            }
        }else if( $add_account_type == 4 ){
            // 获取有分数，且开启的所有三方用户id
            $userservice = app(UserService::class);
            $user_id_array = array_flatten($userservice->getAllQuotaLargeAmount(1,$this->price )->toArray());
            if(!count($user_id_array))
            {
                return RespCode::PARAMETER_ERROR_STOP;
            }

            if( $this->pay_code == 'alipay_packets' || $this->pay_code == 'alipay' || $this->pay_code == 'wechat' || $this->pay_code == 'cloudpay')
            {
                $account_list = $this->accountPhoneService->getStatusAndAccountTypeAndUidarr($type,1,$user_id_array);
            }else if($this->pay_code == 'alipay_bank'){
                $account_list = $this->accountBankCardsService->getStatusAndUidarrAndBanKMark(1,$user_id_array,'ANTBANK');
            }else if($this->pay_code == 'alipay_bank2' || $this->pay_code == 'bank_solidcode'){

                $account_list = $this->accountBankCardsService->getStatusAndAccountTypeAndUidarrAndNotBanKMark(1,$user_id_array,'ANTBANK',$type);
            }else if($this->pay_code == 'alipay_solidcode'||$this->pay_code == 'wechat_solidcode'||$this->pay_code == 'cloudpay_solidcode'){
                $account_list = $this->accountPhoneService->getStatusAndAccountTypeAndSolidcodeAndUidarr($type,$user_id_array,1);
            }
        }

        if(!count($account_list))
        {
            return RespCode::ACCOUNT_NOT_START;
        }

        //根据编码选择对应的账号
        if( $this->pay_code == 'alipay_packets' || $this->pay_code == "alipay"||$this->pay_code =='alipay_solidcode')
        {
            $valid_account = $this->getValidAlipayAccount($account_list);
        }else if($this->pay_code == "wechat"||$this->pay_code =='wechat_solidcode'){

            $valid_account = $this->getValidWechatAccount($account_list);
        }else if($this->pay_code == "alipay_bank"){
            $valid_account = $this->getValidBankcardAccount($account_list);
        }else if($this->pay_code == "alipay_bank2" || $this->pay_code == "bank_solidcode"){
            $valid_account = $this->getValidBankcard($account_list);
        }else if($this->pay_code == "cloudpay"||$this->pay_code =='cloudpay_solidcode'){
            $valid_account = $this->getValidCloudpay($account_list);
        }

        if(!count($valid_account)) {

            return RespCode::APP_ERROR;
        }

        return $valid_account;
    }

    /**
     * 选择支付宝账号
     * @param Collection $account_list
     * @return array
     */
    protected function getValidAlipayAccount(Collection $account_list){
        Redis::select(1);
        $valid_account_list = [];
        foreach ($account_list as $k=>$account)
        {
            if( Redis::exists($account->phone_id.'alipay') )
            {
                $get_account = Redis::hGetAll($account->phone_id.'alipay');
                //检查心跳
                if( (time() > (strtotime($get_account['update'])+50) && $get_account['status'] == 1 ) )
                {
                    continue;
                }
                // 验证手机和支付宝id是否一致
                if($get_account['userid'] != $account->alipayuserid &&$this->pay_code=='alipay'){
                    continue;
                }
                // 验证账号是否限额
                if( $account->dayQuota && bcadd($this->price,$get_account['amount'],2) > $account->dayQuota )
                {
                    continue;
                }

                $valid_account_list[$k] = [
                    'account'   => $account->account,
                    'phone_id'  => $account->phone_id,
                    'type'      => $this->pay_code,
                    'userId'    => $account->alipayuserid,
                    'username'  => $account->alipayusername,
                    'phone_uid' => $account->user_id,
                    'qrcode'    => $account->qrcode,
                    'weight'    => $get_account['weight'], // 权重
                ];
            }
        }
        if(!count($valid_account_list)) return [];
        $valid_account = $this->getOrderAccount($valid_account_list);
        $this->updateAccountWeight($valid_account['phone_id'].'alipay');
        return $this->pay_code=='alipay_solidcode'?$this->chooseaPayAmount($valid_account):$valid_account;
    }

    /**
     * 根据时间权重取号
     * @param array $account_list
     * @return array
     */
    protected function getOrderAccount(array $account_list){

        foreach ($account_list as $k=>$v){
            // 优先选着初始化的账号
            if($v['weight'] == 1){
                return $account_list[$k];
            }
        }
        usort($account_list,"sortWeight");
        return array_shift($account_list);
    }



    /**
     * 更新账号权重时间
     * @param string $key
     */
    protected function updateAccountWeight(string $key){
        Redis::hset($key, 'weight', time());
    }

    /**
     * 获取云闪付账号
     * @param Collection $account_list
     * @return array
     */
    protected function getValidCloudpay(Collection $account_list)
    {
        Redis::select(1);
        $valid_account_list = [];
        foreach ($account_list as $k=>$account)
        {
            if( Redis::exists($account->phone_id.'cloudpay') )
            {
                $get_account = Redis::hGetAll($account->phone_id.'cloudpay');
                // 验证手机和支付宝id是否一致
                if($get_account['account'] != $account->account || (time() > (strtotime($get_account['update'])+35) && $get_account['status'] == 1 ) )
                {
                    continue;
                }
                // 验证账号是否限额
                if( $account->dayQuota && bcadd($this->price,$get_account['amount'],2) > $account->dayQuota )
                {
                    continue;
                }

                $valid_account_list[$k] = [
                    'account'   => $account->account,
                    'phone_id'  => $account->phone_id,
                    'type'      => $this->pay_code,
                    'phone_uid' => $account->user_id,
                    'qrcode'    => $account->qrcode,
                ];
            }
        }

        if(!count($valid_account_list))
        {
            return [];
        }
        $rank_key = array_rand($valid_account_list);
        $valid_account= $valid_account_list[$rank_key];

        return $this->pay_code=='cloudpay_solidcode'?$this->chooseaPayAmount($valid_account):$valid_account;
    }

    /**
     * 选择实时微信
     * @param Collection $account_list
     * @return array
     */
    protected function getValidWechatAccount(Collection $account_list)
    {
        Redis::select(1);
        $valid_account_list = [];
        foreach ($account_list as $k=>$account)
        {
            if( Redis::exists($account->phone_id.'wechat') )
            {
                $get_account = Redis::hGetAll($account->phone_id.'wechat');
                // 验证账号是否一致
                if( $get_account['account'] != $account->account || (time() > (strtotime($get_account['update'])+35) && $get_account['status'] == 1 ) )
                {
                    continue;
                }
                // 验证账号是否限额
                if( $account->dayQuota && bcadd($this->price,$get_account['amount'],2) > $account->dayQuota )
                {
                    continue;
                }

                $valid_account_list[$k] = [
                    'type'              => $this->pay_code,
                    'account'           => $account->account,
                    'phone_id'          => $account->phone_id,
                    'phone_uid'         => $account->user_id,
                    'qrcode'    => $account->qrcode,
                    'weight'    => $get_account['weight'],
                ];
            }
        }
        if(!count($valid_account_list)) return [];
        $valid_account = $this->getOrderAccount($valid_account_list);
        $this->updateAccountWeight($valid_account['phone_id'].'wechat');
        return $this->pay_code=='wechat_solidcode'?$this->chooseaPayAmount($valid_account):$valid_account;
    }

    /**
     * 选择银行卡
     * @param Collection $account_list
     * @return array
     */
    protected function getValidBankcard(Collection $account_list)
    {
        Redis::select(1);
        $valid_account_list = [];
        foreach ($account_list as $k=>$account)
        {
            if( Redis::exists($account->phone_id.'bankmsg') )
            {
                $get_account = Redis::hGetAll($account->phone_id.'bankmsg');
                // 验证手机心跳是否正常
                if( (time() > (strtotime($get_account['update'])+60) && $get_account['status'] == 1 ) )
                {
                    continue;
                }
                // 验证账号是否限额
                if( $account->dayQuota && bcadd($this->price,$get_account['amount'],2) > $account->dayQuota )
                {
                    continue;
                }

                $valid_account_list[$k] = [
                    'type'              => $this->pay_code,
                    'account'           => $account->cardNo,
                    'bank_account'      => $account->bank_account,
                    'phone_id'          => $account->phone_id,
                    'bank_account_name' => $account->bank_account,
                    'bank_name'         => $account->bank_name,
                    'bank_code'         => $account->bank_mark,
                    'chard_index'       => $account->chard_index,
                    'phone_uid'         => $account->user_id,
                    'cardNo'            => $account->cardNo,
                    'qrcode'            => $account->qrcode,
                ];
            }
        }

        if(!count($valid_account_list))return [];
        $rank_key = array_rand($valid_account_list);
        $valid_account = $valid_account_list[$rank_key];

        if(!$valid_account)return [];


        // 截取银行卡号后四位
        $card = substr($valid_account['cardNo'],-4);

        // 实现金额唯一;
        $flag = false;
        for ($i=10;$i >= 0;$i--){
            $key = $valid_account['phone_id'].'_'.$this->pay_code.'_'.$card.'_'.sprintf('%0.2f',$this->price);
            if( $this->existsAmount($key) )
            {
                $flag = true;
                break;
            }else{
                $this->price = bcsub($this->price,0.01,2);
            }
        }
        if(!$flag)
        {
            return [];
        }
        $valid_account['realPrice'] = $this->price;

        return $valid_account;
    }

    /**
     * 选择网商银行卡
     * @param Collection $account_list
     * @return array
     */
    protected function getValidBankcardAccount(Collection $account_list)
    {
        Redis::select(1);
        $valid_account_list = [];
        foreach ($account_list as $k=>$account)
        {
            if( Redis::exists($account->phone_id.'alipay') )
            {
                $get_account = Redis::hGetAll($account->phone_id.'alipay');
                // 验证手机和支付宝id是否一致
                if( (time() > (strtotime($get_account['update'])+35) && $get_account['status'] == 1 ) )
                {
                    continue;
                }
                // 验证账号是否限额
                if( $account->dayQuota && bcadd($this->price,$get_account['bankamount'],2) > $account->dayQuota )
                {
                    continue;
                }

                $valid_account_list[$k] = [
                    'type'              => $this->pay_code,
                    'account'           => $account->cardNo,
                    'phone_id'          => $account->phone_id,
                    'bank_account_name' => $account->bank_account,
                    'bank_name'         => $account->bank_name,
                    'bank_code'         => $account->bank_mark,
                    'chard_index'       => $account->chard_index,
                    'phone_uid'         => $account->user_id
                ];
            }
        }
        if(!count($valid_account_list))return [];

        $rank_key = array_rand($valid_account_list);
        $valid_account = $valid_account_list[$rank_key];

        if(!$valid_account)return [];
        return $this->chooseaPayAmount($valid_account);
    }



    /**选择金额,保证一台手机(账号)金额唯一
     * @param $valid_account
     * @return bool
     */
    protected function chooseaPayAmount($valid_account){

        $flag = false;
        for ($i=10;$i >= 0;$i--){
            $key = $valid_account['phone_id'].'_'.$this->pay_code.'_'.sprintf('%0.2f',$this->price);
            if( $this->existsAmount($key) )
            {
                $flag = true;
                break;
            }else{
                $this->price = bcsub($this->price,0.01,2);
            }
        }
        if($flag){
            //成功选取金额
            $valid_account['realPrice'] = $this->price;
        }else{
            //选取金额失败
            $valid_account=[];
        }

        return $valid_account;
    }

    /**
     * 检测金额是否唯一
     * @param string $key
     * @return bool
     */
    protected function existsAmount(string $key){
        if( Redis::exists($key) )
        {
            return false;
        }else{

            Redis::set($key,$this->price);
            Redis::expire($key,600);
            return true;
        }
    }
}