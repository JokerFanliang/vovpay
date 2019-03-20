<?php
/**
 * 收款助手登录
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/1/7
 * Time: 11:49
 */

namespace App\Http\Pay\Controllers;

use App\Services\AccountBankCardsService;
use App\Services\AccountPhoneService;
use App\Services\UserService;
use App\Services\AdminsService;
use Illuminate\Http\Request;

class PhoneLoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $userService;
    protected $adminsService;
    protected $accountPhoneService;
    protected $accountBankCardsService;

    /**
     * PhoneLoginController constructor.
     * @param AccountBankCardsService $accountBankCardsService
     * @param UserService $userService
     * @param AdminsService $adminsService
     * @param AccountPhoneService $accountPhoneService
     */
    public function __construct( AccountBankCardsService $accountBankCardsService ,UserService $userService, AdminsService $adminsService, AccountPhoneService $accountPhoneService)
    {
        $this->userService   = $userService;
        $this->adminsService = $adminsService;
        $this->accountPhoneService = $accountPhoneService;
        $this->accountBankCardsService = $accountBankCardsService;
    }

    public function login(Request $request)
    {

        $add_account_type = env('ADD_ACCOUNT_TYPE');
        if($add_account_type == 2)
        {
            $type = 'admin';
            $fans = $this->adminsService->findUsernameAndStatus($request->input('username'),1);
            if($fans)
            {
                $fans->apiKey = env('SIGNKEY');
            }
        }else{
            $type = '';//商户
            $fans = $this->userService->findUsernameAndStatus($request->input('username'),1);
        }

        if(!$fans)
        {
            return json_encode(array('msg'=>'用户名或密码错误'));
        }

        switch ($add_account_type){
            case '1':
                if($fans->group_type != 1) return json_encode(array('msg'=>'无权使用'));
                break;
            case '3' ;
                if($fans->group_type != 2) return json_encode(array('msg'=>'无权使用'));
                break;
            case '4' ;
                if($fans->group_type != 3) return json_encode(array('msg'=>'无权使用'));
                break;
        }

        if (!password_verify($request->input('password'), $fans->password)) {
            return json_encode(array('msg'=>'用户名或密码错误'));
        }

        if($type == 'admin')
        {
            $account_list = $this->accountPhoneService->getPhoneidAndStatusAndUserid($request->input('phoneid'),1,100000);

            if(!count($account_list))
            {
                $account_list = $this->accountBankCardsService->findPhoneIdAndUserId($request->input('phoneid'),100000);
            }

        }else{
            $account_list = $this->accountPhoneService->getPhoneidAndStatusAndUserid($request->input('phoneid'),1,$fans->id);

            if(!count($account_list))
            {
                $account_list = $this->accountBankCardsService->findPhoneIdAndUserId($request->input('phoneid'),$fans->id);
            }
        }

        if(!count($account_list))
        {
            return json_encode(array('msg'=>'后台未配置收款账号'));
        }

        foreach ($account_list as $k=>$v)
        {
            switch ($v->accountType){
                case "微信":
                    $wx_account = $v->account;
                    break;
                case "支付宝":
                    $alipay_account = $v->account;
                    break;
                case "云闪付":
                    $cloudpay_account = $v->account;
                    break;
            }
        }

        $data = array(
            'alipayAccount' => isset($alipay_account) ? $alipay_account : '',
            'wechatAccount' => isset($wx_account) ? $wx_account : '',
            'cloudpayAccount'=> isset($cloudpay_account) ? $cloudpay_account : '',//云闪付
            'key'           => $fans->apiKey,
            'host'          => env('MQ_HOST'),
            'Port'          => env('MQ_PORT'),
            'virtualhost'   => env('MQ_VHOST'),
            'qname'         => env('MQ_USER'),
            'qpassword'     => env('MQ_PWD'),
            'msg'           => '',
            'banks'         => [
                ['phone' => '95588', 'name' => '中国工商银行'],
                ['phone' => '95533', 'name' => '中国建设银行'],
                ['phone' => '95599', 'name' => '中国农业银行'],
                ['phone' => '95555', 'name' => '招商银行'],
                ['phone' => '95561', 'name' => '兴业银行'],
                ['phone' => '95566', 'name' => '中国银行'],
                ['phone' => '95568', 'name' => '民生银行'],
                ['phone' => '95508', 'name' => '广发银行'],
                ['phone' => '95511', 'name' => '平安银行'],
                ['phone' => '95558', 'name' => '中信银行'],
            ],
        );


        return json_encode($data);

    }
}
