<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/20
 * Time: 10:12
 */

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Repositories\WithdrawsRepository;
use App\Repositories\BankCardRepository;
use App\Repositories\StatisticalRepository;
use App\Exceptions\CustomServiceException;
use App\Repositories\SystemsRepository;
use Illuminate\Support\Facades\DB;


class WithdrawsService
{
    protected $withdrawsRepository;
    protected $bankCardRepository;
    protected $statisticalRepository;

    public function __construct(WithdrawsRepository $withdrawsRepository, BankCardRepository $bankCardRepository, StatisticalRepository $statisticalRepository)
    {
        $this->withdrawsRepository = $withdrawsRepository;
        $this->bankCardRepository = $bankCardRepository;
        $this->statisticalRepository = $statisticalRepository;


    }

    /**账户结算(提款)处理
     * @param array $data
     * @return array
     */
    public function add(array $data)
    {
        $user = Auth::user();
        $data['user_id'] = $user->id;
        $data['agent_id'] = $user->group_type == 1 ? $user->parentId : '0';

        //获取提款规则信息
        $withdrawRule = $this->getWithdrawRule($data);
        //手续费计算
        $this->caculateRate($data, $withdrawRule);
        //验证账户余额
        $this->accountVerify($data, $withdrawRule);
        //填充结算银行卡/订单等信息
        $data = $this->buildWithdrawInfo($data);

        // 去掉无用数据
        $data = array_except($data, ['_token', 'auth_code', 'bank_id']);

        DB::connection('mysql')->transaction(function () use ($data) {
            //账户余额更新
            $status = $this->statisticalRepository->updateUseridBalanceDecrement($data['user_id'], $data['withdrawAmount']);
            //添加结算信息
            $status && ($status = $this->withdrawsRepository->add($data));

            if (!$status) {
                throw  new CustomServiceException('系统错误,结算申请失败');
            }
        }, 2);

        return true;
    }


    /**
     * 获取提款规则信息
     * @return array
     */
    public function getWithdrawRule()
    {
        $withdrawRule = [];
        $withdrawRule['withdraw_downline']  = SystemsRepository::findKey('withdraw_downline');
        $withdrawRule['withdraw_fee_type']  = SystemsRepository::findKey('withdraw_fee_type');
        $withdrawRule['withdraw_rate']      = SystemsRepository::findKey('withdraw_rate');
        return $withdrawRule;
    }

    /**金额验证
     * @return bool
     */
    protected function accountVerify(&$data, $withdrawRule)
    {
        if ($withdrawRule['withdraw_downline'] > $data['withdrawAmount']) {
            throw   new CustomServiceException('提现金额不能少于' . $withdrawRule['withdraw_downline'] . '元');
        }
        $useraccount = $this->statisticalRepository->findUserId($data['user_id']);
        if ($useraccount['handlingFeeBalance'] < $data['withdrawAmount']) {
            throw   new CustomServiceException('提现金额超过账户可提现金额');
        }
        // 提现金额不能小于手续费
        if ($data['withdrawRate'] >= $data['withdrawAmount']) {
            throw   new CustomServiceException('提现金额过低');
        }
    }

    /**计算提款手续费
     * @param $data
     * @param $withdrawRule
     */
    protected function caculateRate(&$data, $withdrawRule)
    {
        switch ($withdrawRule['withdraw_fee_type']) {
            case 'RATE':
                $data['withdrawRate'] = bcmul($data['withdrawAmount'], bcdiv($withdrawRule['withdraw_rate'], 100, 4), 2);
                $data['toAmount'] = bcsub($data['withdrawAmount'], $data['withdrawRate'], 2);
                break;
            case 'FIX':
                $data['withdrawRate'] = $withdrawRule['withdraw_rate'];
                $data['toAmount'] = bcsub($data['withdrawAmount'], $data['withdrawRate'], 2);
                break;
            default :
                throw new  CustomServiceException('不存在的提款收费类型:' . $withdrawRule['withdraw_fee_type'] . ',请联系平台管理员!');
        }
    }

    /**添加提款记录
     * @param $data
     * @return mixed
     */

    protected function buildWithdrawInfo($data)
    {

        //银行卡信息
        $bankCard = $this->bankCardRepository->findId($data['bank_id']);
        if (!$bankCard || $bankCard['user_id'] != $data['user_id']) {
            throw   new CustomServiceException('指定银行卡不存在');
        }
        //银行信息
        $bankInfo = $bankCard->Bank->toArray();
        if (!$bankCard) {
            throw   new CustomServiceException('不支持的银行');
        }

        $data['accountName'] = $bankCard['accountName'];
        $data['branchName'] = $bankCard['branchName'];
        $data['bankCardNo'] = $bankCard['bankCardNo'];

        $data['bankName'] = $bankInfo['bankName'];
        $data['bankCode'] = $bankInfo['code'];

        //提款流水号
        $data['orderId'] = $this->buildWithdrawOrderid();

        return $data;

    }


    /**
     * 搜索分页
     * @param array $data
     * @param int $page
     * @return mixed
     */
    public function searchPage(array $data, int $page)
    {
        $sql = '1=1';
        $where = [];;

        if (isset($data['created_at'])) {
            $time = explode(" - ", $data['created_at']);
            if (isset($time[0]) && $time[0]) {
                $sql .= ' and created_at >= ?';
                $where['created_at'] = $time[0];
            }
            if (isset($time[1]) && $time[1]) {
                $sql .= ' and created_at <= ?';
                $where['updated_at'] = $time[1];
            }
        }

        if (isset($data['orderId'])) {
            $sql .= ' and orderId = ?';
            $where['orderId'] = $data['orderId'];
        }
        if (isset($data['channel_id']) && $data['channel_id'] != '-1') {
            $sql .= ' and channel_id = ?';
            $where['channel_id'] = $data['channel_id'];
        }
        if (isset($data['user_id']) && $data['user_id']) {
            $sql .= ' and user_id = ?';
            $where['user_id'] = $data['user_id'];
        }
        if (isset($data['agent_id']) && $data['agent_id']) {
            $sql .= ' and agent_id = ?';
            $where['agent_id'] = $data['agent_id'];
        }
        if (isset($data['outOrderId']) && $data['outOrderId']) {
            $sql .= ' and outOrderId = ?';
            $where['outOrderId'] = $data['outOrderId'];
        }
        if (isset($data['status']) && $data['status'] != '-1') {
            $sql .= ' and status = ?';
            $where['status'] = $data['status'];
        }
        $serach['list'] = $this->withdrawsRepository->searchPage($sql, $where, $page);
        $serach['info'] = $this->withdrawsRepository->searchWithdrawInfoSum($sql, $where, $page);
        return $serach;
    }

    /**普通结算处理
     * @param $info
     */
    public function commonWithdraw($info)
    {
        $id = $info['id'];
        $info = array_except($info, ['_token', 'id']);

        if ($info['status'] == 2) {
            $info['channelCode'] = $info['channelCode'] == 0 ? '' : $info['channelCode'];
            $status = $this->withdrawsRepository->update($id, $info);
        } elseif ($info['status'] == 4) {
            //取消结算
            $this->cancelWithdraw($id, $info);
            $status = true;
        }
        return $status;
    }

    /**代付结算处理
     * @param $info
     */
    public function paidWithdraw($info)
    {
        $id = $info['id'];
        $info = array_except($info, ['_token', 'id']);

        if ($info['status'] == 1) {
            //修改状态
            $status = $this->withdrawsRepository->update($id, $info);
            //调用代付接口
        } elseif ($info['status'] == 4) {
            //取消结算
            $this->cancelWithdraw($id, $info);
            $status = true;
        }
        return isset($status)?$status:false;
    }

    /**取消结算
     * @param $id
     * @param $info
     */
    private function cancelWithdraw($id, $info)
    {

        DB::connection('mysql')->transaction(function () use ($id, $info) {

            //获取结算详情
            $withdrawInfo = $this->withdrawsRepository->findById($id);
            //账户余额更新
            $status = $this->statisticalRepository->updateUseridBalanceIncrement($withdrawInfo->user_id, $withdrawInfo->withdrawAmount);
            //资金变动记录
//          $this->addMoneyDetail($data);
            //修改结算记录
            $withdrawInfo->status = $info['status'];
            $withdrawInfo->comment = $info['comment'];;
            $status && $status = $withdrawInfo->save();

            if (!$status) {
                throw new CustomServiceException('取消结算操作失败');
            }
        }, 2);
    }

    /**代理处理结算鉴权
     * @param $agentId
     * @param $wOrderId
     */
    public function agentAuthorize($agent_id, $withdraw_id)
    {
        if (env('ADD_ACCOUNT_TYPE') != 3) {
            throw new CustomServiceException('非法操作!');
        }
        $withdrawInfo=$this->withdrawsRepository->findById($withdraw_id);
        if($withdrawInfo->agent_id!=$agent_id){

            throw new CustomServiceException('非法操作!');
        }
    }


    /**生成提款订单号
     * @return string
     */
    private function buildWithdrawOrderid()
    {
        return 'W' . date('YmdHis') . mt_rand(10000, 99999);
    }
    /**
     * 查询是否存在申请
     * return mixed
     */
    public function checkNotice()
    {
        return $this->withdrawsRepository->getCount();
    }
}