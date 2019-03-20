<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/12/21
 * Time: 17:13
 */

namespace App\Repositories;

use App\Models\Account_phone;

class AccountPhoneRepository
{
    protected $account_phone;

    /**
     * AccountPhoneRepository constructor.
     * @param Account_phone $account_phone
     */
    public function __construct(Account_phone $account_phone)
    {
        $this->account_phone = $account_phone;
    }

    /**
     * @param string $sql
     * @param array $where
     * @param string $time
     * @param int $page
     * @return mixed
     */
    public function searchPage(string $sql, array $where, string $time, int $page)
    {
        if (!$time) {
            $time = date('Y-m-d');
        }
        return $this->account_phone->whereRaw($sql, $where)
            ->leftjoin('account_day_counts', function ($join) use ($time) {
                $join->on('account_day_counts.account', '=', 'account_phones.account')
                    ->whereDate('account_day_counts.updated_at', "=", $time);
            })
            ->selectRaw('pay_account_phones.*,account_amount,account_order_count,account_order_suc_count,cast(account_order_suc_count/account_order_count as decimal(10,2))*100 as success_rate')
            ->paginate($page);
    }

    /**
     * @param string $sql
     * @param array $where
     * @param int $page
     * @return mixed
     */
    public function searchPhone(string $sql, array $where, int $page)
    {
        return $this->account_phone->whereRaw($sql, $where)->paginate($page);
    }

    /**
     * 添加
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $string = strtolower(str_random(32));

        $result = $this->account_phone->where('signKey', '=', $string)->first();
        if (!isset($result)) {
            $data['signKey'] = $string;
        } else {
            return false;
        }
        return $this->account_phone->create($data);
    }

    /**
     * @param int $id
     * @param int $uid
     * @param array $data
     * @return mixed
     */
    public function update(int $id, int $uid, array $data)
    {
        if ($uid == 1) {
            return $this->account_phone->whereId($id)->update($data);
        } else {
            return $this->account_phone->whereId($id)->whereUserId($uid)->update($data);
        }

    }

    /**
     * @param int $id
     * @param string $value
     * @return mixed
     */
    public function searchCheck(int $id = null, string $value, $name = null)
    {
        if ($id) {
            $sql = ' 1=1 ';
            $where = [];
            if (isset($value) && $value != null) {
                $sql .= "and id <> $id and phone_id = ?";
                $where['phone_id'] = $value;
            }

            if (isset($name) && $name != null) {
                $sql .= " and accountType = ?";
                $where['accountType'] = $name;
            }

        } else {
            $sql = ' 1=1 ';
            $where = [];

            if (isset($value) && $value != null) {
                $sql .= "and phone_id = ?";
                $where['phone_id'] = $value;
            }

            if (isset($name) && $name != null) {
                $sql .= " and accountType = ?";
                $where['accountType'] = $name;
            }

        }
        return $this->account_phone->whereRaw($sql, $where)->first();
    }

    /**
     * @param int $id
     * @param int $uid
     * @return mixed
     */
    public function findIdAndUserId(int $id, int $uid)
    {
        $data=$this->account_phone->whereId($id)->whereUserId($uid)->first();
        $data->qrcode=$data->qrcode=='0'?'':$data->qrcode;
        return $data;
    }

    /**
     * @param int $id
     * @param int $uid
     * @return mixed
     */
    public function del(int $id, int $uid)
    {
        return $this->account_phone->whereId($id)->whereUserId($uid)->delete();
    }

    /**
     * @param string $type
     * @param int $uid
     * @param int $status
     * @return mixed
     */
    public function getStatusAndAccountType(string $type, int $uid, int $status)
    {
        if ($type == 'alipay') {
            $type = '支付宝';
        } elseif ($type == 'wechat') {
            $type = '微信';
        } else if($type == 'cloudpay'){
            $type = '云闪付';
        } else if($type == 'alipay_packets'){
            $type = '支付宝';
        }
        return $this->account_phone->whereStatus($status)->whereUserId($uid)->whereAccounttype($type)->get();
    }
    /**
     * @param string $type
     * @param int $uid
     * @param int $status
     * @return mixed
     */
    public function getStatusAndAccountTypeAndSolidcode(string $type, int $uid, int $status)
    {
        if ($type == 'alipay_solidcode') {
            $type = '支付宝';
        } elseif ($type == 'wechat_solidcode') {
            $type = '微信';
        } else if($type == 'cloudpay_solidcode'){
            $type = '云闪付';
        }
        return $this->account_phone->whereStatus($status)->whereUserId($uid)->whereAccounttype($type)->where('qrcode','<>','0')->get();
    }

    /**
     * 查询账户指定用户名下的所有账户
     * @param string $type
     * @param int $status
     * @return mixed
     */
    public function getStatusAndAccountTypeAndUidarr(string $type, int $status, array $uid_arr)
    {
        if ($type == 'alipay') {
            $type = '支付宝';
        } elseif ($type == 'wechat') {
            $type = '微信';
        } else if($type == 'cloudpay'){
            $type = '云闪付';
        } else if($type == 'alipay_packets'){
            $type = '支付宝';
        }
        return $this->account_phone->whereStatus($status)->whereIn('user_id', $uid_arr)->whereAccounttype($type)->get();
    }
    /**
     * 查询账户指定用户名下的所有账户
     * @param string $type
     * @param int $status
     * @return mixed
     */
    public function getStatusAndAccountTypeAndSolidcodeAndUidarr(string $type, int $status, array $uid_arr)
    {
        if ($type == 'alipay_solidcode') {
            $type = '支付宝';
        } elseif ($type == 'wechat_solidcode') {
            $type = '微信';
        } else if($type == 'cloudpay_solidcode'){
            $type = '云闪付';
        }
        return $this->account_phone->whereStatus($status)->whereIn('user_id', $uid_arr)->whereAccounttype($type)->where('qrcode','<>','0')->get();
    }

    /**
     * @param string $phoneId
     * @param int $status
     * @param int $uid
     * @return mixed
     */
    public function getPhoneidAndStatusAndUserid(string $phoneId, int $status, int $uid)
    {
        return $this->account_phone->whereStatus($status)->wherePhoneId($phoneId)->whereUserId($uid)->get();
    }

}