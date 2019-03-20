<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/1/15
 * Time: 17:35
 */

namespace App\Repositories;


use App\Models\Account_bank_cards;

class AccountBankCardsRepository
{
    protected $account_bank_cards;

    public function __construct(Account_bank_cards $account_bank_cards)
    {
        $this->account_bank_cards = $account_bank_cards;
    }


    /**
     * @param array $data
     * @param int $page
     * @return mixed
     */
    public function searchPage(array $data, int $page)
    {
        $sql = ' 1=1 ';
        $where = [];

        if (isset($data['bank_account']) && $data['bank_account']) {
            $sql .= 'and bank_account = ?';
            $where['bank_account'] = $data['bank_account'];
        }
        if (isset($data['user_id']) && $data['user_id']) {
            $sql .= ' and pay_account_bank_cards.user_id = ?';
            $where['user_id'] = $data['user_id'];
        }
        if (isset($data['accountType'])) {
            $sql .= ' and pay_account_bank_cards.accountType = ?';
            $where['accountType'] = $data['accountType'];
        }

        if (isset($data['third']) && $data['third']){
            $sql .= ' and pay_account_bank_cards.third = ?';
            $where['third'] = $data['third'];
        }

        $time = isset($data['searchTime']) ? $data['searchTime'] : date('Y-m-d',time());
        return $this->account_bank_cards->whereRaw($sql, $where)
            ->leftjoin('account_day_counts',function ($join) use($time){
                $join->on('account_day_counts.account', '=', 'account_bank_cards.cardNo')
                    ->whereDate('account_day_counts.updated_at',"=", $time);
            })
            ->selectRaw('pay_account_bank_cards.*,account_amount,account_order_count,account_order_suc_count,cast(account_order_suc_count/account_order_count as decimal(10,2))*100 as success_rate')
            ->paginate($page);


    }

    /**
     * @param array $data
     * @return bool
     */
    public function add(array $data)
    {
        $string = strtolower(str_random(32));

        $result = $this->account_bank_cards->where('signKey', '=', $string)->first();
        if (!isset($result)) {
            $data['signKey'] = $string;
        } else {
            return false;
        }
        return $this->account_bank_cards->create($data);
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
            return $this->account_bank_cards->whereId($id)->update($data);
        }else{
            return $this->account_bank_cards->whereId($id)->whereUserId($uid)->update($data);
        }

    }


    /**
     * @param int $id
     * @param int $uid
     * @return mixed
     */
    public function findIdAndUserId(int $id, int $uid)
    {
        return $this->account_bank_cards->whereId($id)->whereUserId($uid)->first();
    }

    /**
     * @param string $id
     * @param int $uid
     * @return mixed
     */
    public function findPhoneIdAndUserId(string $id, int $uid)
    {
        return $this->account_bank_cards->wherePhoneId($id)->whereUserId($uid)->get();
    }

    /**
     * @param int $id
     * @param int $uid
     * @return mixed
     */
    public function del(int $id,int $uid)
    {
        return $this->account_bank_cards->whereId($id)->whereUserId($uid)->delete();
    }

    /**
     * @param int $uid
     * @param int $status
     * @param string $bank_mark
     * @return mixed
     */
    public function getStatusAndUserIdAndBanKMark(int $uid, int $status,string $bank_mark)
    {
        return $this->account_bank_cards->whereStatus($status)->whereUserId($uid)->whereBankMark($bank_mark)->get();
    }

    /**
     * @param int $uid
     * @param int $status
     * @param string $bank_mark
     * @param $type
     * @return mixed
     */
    public function getStatusAndAccountTypeAndUserIdAndNotBanKMark(int $uid, int $status,string $bank_mark,$type)
    {
        if ($type == 'alipay_bank2' || $type == 'alipay_bank') {
            $type = '银行卡';
        } elseif ($type == 'bank_solidcode') {
            $type = '银行固码';
        }
        return $this->account_bank_cards->whereStatus($status)->whereUserId($uid)->whereAccounttype($type)->where('bank_mark','<>',$bank_mark)->get();
    }

    /**
     * @param int $status
     * @param array $uid_arr
     * @param string $bank_mark
     * @return mixed
     */
    public function getStatusAndUidarrAndBanKMark(int $status, array $uid_arr ,string $bank_mark)
    {
        return $this->account_bank_cards->whereStatus($status)->whereIn('user_id',$uid_arr)->whereBankMark($bank_mark)->get();
    }

    /**
     * @param int $status
     * @param array $uid_arr
     * @param string $bank_mark
     * @return mixed
     */
    public function getStatusAndAccountTypeAndUidarrAndNotBanKMark(int $status, array $uid_arr ,string $bank_mark,$type)
    {
        if ($type == 'alipay_bank2' || $type == 'alipay_bank') {
            $type = '银行卡';
        } elseif ($type == 'bank_solidcode') {
            $type = '银行固码';
        }

        return $this->account_bank_cards->whereStatus($status)->whereAccounttype($type)->whereIn('user_id',$uid_arr)->where('bank_mark','<>',$bank_mark)->get();
    }

    /**
     * 检测唯一
     * @param string $field
     * @param string $value
     * @param int|null $id
     * @return mixed
     */
    public function searchCheck(string $field, string $value, int $id = null)
    {
        if ($id) {
            if ($field == 'cardNo') {
                $sql = "id <> $id and cardNo = ?";
                $where['cardNo'] = $value;
            }
        } else {
            if ($field == 'cardNo') {
                $sql = " cardNo = ?";
                $where['cardNo'] = $value;

            }
        }
        return $this->account_bank_cards->whereRaw($sql, $where)->first();
    }


}