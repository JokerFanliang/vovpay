<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/12/20
 * Time: 13:35
 */
namespace App\Services\Pay;

use App\Models\Channel;
use App\Models\Channel_payment;
use App\Models\User;
use App\Models\User_rates;
use App\Services\ChooseAccountService;
use App\Services\OrdersService;
use App\Services\RabbitMqService;
use Illuminate\Http\Request;
use App\Jobs\SendOrderAsyncNotify;
use App\Common\RespCode;
use Illuminate\Support\Facades\Redis;

class ExemptService implements PayInterface
{
    public function pay(User $user, Channel $channel, Channel_payment $Channel_payment, User_rates $user_rates, Request $request )
    {
        // 随机选号
        $ChooseAccountService = app(ChooseAccountService::class);
        $account_array        = $ChooseAccountService->getAccount($user,$request->pay_code, $request->amount);
        if( isset($account_array['respCode']) )
        {
            return json_encode($account_array,JSON_UNESCAPED_UNICODE);
        }
        $account_array['amount'] = $request->amount;

        // 订单添加
        $ordersService  = app(OrdersService::class);
        $result         = $ordersService->add($user, $channel, $Channel_payment, $request, $user_rates, $account_array);

        if(!$result)
        {
            return json_encode(RespCode::FAILED,JSON_UNESCAPED_UNICODE);
        }

        Redis::select(1);
        if($request->pay_code == 'alipay') // 支付宝转账
        {
            $order_date = array(
                'amount'  => $result->amount,
                'meme'    => $result->orderNo,
                'userID'  => $account_array['userId'],
                'status'  => 0,
            );

            $data = [
                'type'    => $request->pay_code,
                'username'=> $account_array['username'],
                'money'   => sprintf('%0.2f',$result->amount),
                'orderNo' => $result->orderNo,
                'payurl'  => 'alipays://platformapi/startapp?appId=20000123&actionType=scan&biz_data={"s": "money","u": "'.$account_array['userId'].'","a": "'.$result->amount.'","m": "'.$result->orderNo.'"}',
                'h5url'   => 'alipays://platformapi/startapp?appId=20000067&url='. 'http://'.$_SERVER['HTTP_HOST'].'/pay/h5pay/'. $result->orderNo,
            ];
            Redis::hmset($result->orderNo, $order_date);
            Redis::expire($result->orderNo,600);

        }else if($request->pay_code == 'alipay_bank'){ // 支付宝转网商
            // 存储订单号,以便回调
            $key = $account_array['phone_id'].'_'.$request->pay_code.'_'.sprintf('%0.2f',$result['amount']);
            Redis::set($key,$result->orderNo);
            Redis::expire($key,600);

            $order_date = array(
                'amount'  => $result->amount,
                'account' => $account_array['account'],
                'bank_account_name' => $account_array['bank_account_name'],
                'bank_name'  => $account_array['bank_name'],
                'bank_code'  => $account_array['bank_code'],
                'status'  => 0,
            );
            $data = [
                'type'    => $request->pay_code,
                'money'   => sprintf('%0.2f',$result->amount),
                'orderNo' => $result->orderNo,
                'h5url'   => '',
                'payurl'  => "https://www.alipay.com/?appId=09999988&actionType=toCard&sourceId=bill&cardNo={$account_array['account']}&bankAccount={$account_array['bank_account_name']}&money={$result->amount}&amount={$result->amount}&bankMark={$account_array['bank_code']}&bankName={$account_array['bank_name']}&cardIndex={$account_array['chard_index']}&cardNoHidden=true&cardChannel=HISTORY_CARD&orderSource=from",
            ];

            Redis::hmset($result->orderNo, $order_date);
            Redis::expire($result->orderNo,600);

        }else if($request->pay_code == 'wechat'){// 微信转账
            try{
                $msg = json_encode([
                    'amount' => $result->amount,
                    'mark'   => $result->orderNo,
                    'type'   => 'wechat_qr',
                    'sendtime' => TimeMicroTime(),
                ]);
                $rabbitMqService = app(RabbitMqService::class);
                $rabbitMqService->send('qr_'.$account_array['phone_id'].'test',$msg);
                for ($i=0;$i<10;$i++){
                    $qrcode = Redis::get($result->orderNo);
                    if($qrcode)
                    {
                        break;
                    }
                    sleep(1);
                }
                if(!$qrcode) return json_encode(RespCode::QRCODE_ERROR,JSON_UNESCAPED_UNICODE);
                Redis::del($result->orderNo);
                $oRcode = json_decode($qrcode, true);

                $data = [
                    'type'    => $request->pay_code,
                    'money'   => sprintf('%0.2f',$result->amount),
                    'orderNo' => $result->orderNo,
                    'payurl'  => $oRcode['payurl'],
                    'status'  => 0,
                ];

                Redis::hmset($result->orderNo, $data);
                Redis::expire($result->orderNo,180);

            }catch ( \Exception $e){
                return json_encode(RespCode::SYS_ERROR,JSON_UNESCAPED_UNICODE);
            }
        }else if($request->pay_code == 'alipay_bank2'){// 支付宝转银行卡
            // 存储订单号,以便回调
            // 截取银行卡号后四位
            $card = substr($result['account'],-4);
            $key = $account_array['phone_id'].'_'.$request->pay_code.'_'.$card.'_'.sprintf('%0.2f',$result['amount']);
            Redis::set($key,$result->orderNo);
            Redis::expire($key,600);

            $order_date = array(
                'amount'  => $result->amount,
                'account' => $account_array['account'],
                'bank_account_name' => $account_array['bank_account_name'],
                'bank_name'  => $account_array['bank_name'],
                'bank_code'  => $account_array['bank_code'],
                'status'  => 0,
            );
            $data = [
                'type'    => $request->pay_code,
                'money'   => sprintf('%0.2f',$result->amount),
                'orderNo' => $result->orderNo,
                'h5url'   => '',
                'payurl'  => "https://www.alipay.com/?appId=09999988&actionType=toCard&sourceId=bill&cardNo={$account_array['account']}&bankAccount={$account_array['bank_account_name']}&money={$result->amount}&amount={$result->amount}&bankMark={$account_array['bank_code']}&bankName={$account_array['bank_name']}&cardIndex={$account_array['chard_index']}&cardNoHidden=true&cardChannel=HISTORY_CARD&orderSource=from",
            ];

            Redis::hmset($result->orderNo, $order_date);
            Redis::expire($result->orderNo,600);

            $request->pay_code = 'alipay_bank';

        }else if($request->pay_code == 'cloudpay'){// 云闪付
            try{
                $msg = json_encode([
                    'amount' => $result->amount,
                    'mark'   => $result->orderNo,
                    'type'   => 'cloudpay_qr',
                    'sendtime' => TimeMicroTime(),
                ]);
                $rabbitMqService = app(RabbitMqService::class);
                $rabbitMqService->send('qr_'.$account_array['phone_id'].'test',$msg);
                for ($i=0;$i<10;$i++){
                    $qrcode = Redis::get($result->orderNo);
                    if($qrcode)
                    {
                        break;
                    }
                    sleep(1);
                }
                if(!$qrcode) return json_encode(RespCode::QRCODE_ERROR,JSON_UNESCAPED_UNICODE);
                Redis::del($result->orderNo);
                $oRcode = json_decode($qrcode, true);

                $data = [
                    'type'    => $request->pay_code,
                    'money'   => sprintf('%0.2f',$result->amount),
                    'orderNo' => $result->orderNo,
                    'payurl'  => $oRcode['payurl'],
                    'status'  => 0,
                ];

                Redis::hmset($result->orderNo, $data);
                Redis::expire($result->orderNo,180);

            }catch ( \Exception $e){
                return json_encode(RespCode::SYS_ERROR,JSON_UNESCAPED_UNICODE);
            }
        }else if($request->pay_code == 'alipay_solidcode'||$request->pay_code == 'wechat_solidcode'||$request->pay_code == 'cloudpay_solidcode'){
            // 固码
            // 存储订单号,以便回调
            // 截取银行卡号后四位
            $key = $account_array['phone_id'].'_'.$request->pay_code.'_'.sprintf('%0.2f',$result['amount']);
            Redis::set($key,$result->orderNo);
            Redis::expire($key,600);

            $order_date = array(
                'amount'  => $result->amount,
                'account' => $account_array['account'],
                'status'  => 0,
                'meme'    => $result->orderNo,
            );
            $data = [
                'type'    => $request->pay_code,
                'money'   => sprintf('%0.2f',$result->amount),
                'orderNo' => $result->orderNo,
                'h5url'   => '',
                'payurl'  => $account_array['qrcode'],
            ];

            Redis::hmset($result->orderNo, $order_date);
            Redis::expire($result->orderNo,600);
        }else if($request->pay_code == 'bank_solidcode') {
            // 固码
            // 存储订单号,以便回调
            // 截取银行卡号后四位
            $account = $account_array['bank_account'];

            $key = $account_array['phone_id'].'_alipay_bank2_'.$account.'_'.sprintf('%0.2f',$account_array['realPrice']);
            Redis::set($key,$result->orderNo);
            Redis::expire($key,600);

            $order_date = array(
                'amount' => $result->amount,
                'account' => $account_array['bank_account'],
                'status' => 0,
                'meme' => $result->orderNo,
            );

            $data = [
                'type' => $request->pay_code,
                'money' => sprintf('%0.2f', $account_array['realPrice']),
                'orderNo' => $result->orderNo,
                'h5url' => '',
                'payurl' => $account_array['qrcode'],
            ];

            Redis::hmset($result->orderNo, $order_date);
            Redis::expire($result->orderNo, 600);
        }else if($request->pay_code == 'alipay_packets'){ // 支付宝红包
            $order_date = array(
                'amount'  => $result->amount,
                'meme'    => $result->orderNo,
                'userID'  => $account_array['userId'],
                'status'  => 0,
                'type'    => $request->pay_code,
                'account' => $account_array['account'],
                'sweep_num' => 0, // 扫码次数
            );

            $data = [
                'type'    => $request->pay_code,
                'username'=> $account_array['username'],
                'money'   => sprintf('%0.2f',$result->amount),
                'orderNo' => $result->orderNo,
                'payurl'  => 'http://'.$_SERVER['HTTP_HOST'].'/pay/h5pay/'. $result->orderNo,
                'h5url'   => 'alipays://platformapi/startapp?appId=20000067&url='. 'http://'.$_SERVER['HTTP_HOST'].'/pay/h5pay/'. $result->orderNo,
            ];
            Redis::hmset($result->orderNo, $order_date);
            Redis::expire($result->orderNo,180);
            // 为了模板的共用而改变
            $request->pay_code = 'alipay';
        }

        if( isset($request->json) && $request->json == 'json'){

            $data['payurl']=urlencode($data['payurl']);//防止json_encode后中文乱码

            return json_encode(
                ['amount' => $data['money'], 'QRCodeLink' => $data['payurl'],
            ]);
            exit();
        }


        return view("Pay.{$request->pay_code}",compact('data'));
    }

    public function queryOrder()
    {
        // TODO: Implement queryOrder() method.
    }

    /**
     * @param Request $request
     * @return mixed|void
     */
    public function notifyCallback(Request $request)
    {
        // TODO: Implement notifyCallback() method.

        // 订单异步通知
        $ordersService = app(OrdersService::class);
        $orders = $ordersService->findId(6,'collection');
        SendOrderAsyncNotify::dispatch($orders)->onQueue('orderNotify');
    }

    /**
     * @param Request $request
     * @return mixed|void
     */
    public function successCallback(Request $request)
    {
        // TODO: Implement successCallback() method.
        Redis::select(1);
        $order_no = $request->trade_no;

        if(!Redis::exists($order_no))
        {
            return json_encode(array('msg'=>'','status'=>'expired'));
        }

        $data = Redis::hGetAll($order_no);
        if($data['status'] == 0)
        {
            return json_encode(array('msg'=>'','status'=>'inprogress'));

        }else if($data['status'] == '1'){
            return json_encode(array('msg'=>'','status'=>'success'));
        }
    }
}