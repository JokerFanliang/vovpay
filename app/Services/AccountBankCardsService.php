<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/1/15
 * Time: 17:33
 */

namespace App\Services;

use App\Repositories\AccountBankCardsRepository;

class AccountBankCardsService
{
    protected $accountBankCardsRepository;

    public function __construct(AccountBankCardsRepository $accountBankCardsRepository)
    {
        $this->accountBankCardsRepository = $accountBankCardsRepository;
    }

    /**
     * 带分页查询
     * @param array $data
     * @param int $page
     * @return mixed
     */
    public function getAllPage(array $data, int $page)
    {
        return $this->accountBankCardsRepository->searchPage($data, $page);
    }

    /**
     * 添加
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $data = array_except($data, ['_token'], ['bank']);
        $bank = explode(",", $data['bank_name']);
        $data['bank_name'] = $bank[0];
        $data['bank_mark'] = $bank[1];

        if (isset($data['qrcode'])&&!isset($data['chard_index'])){
            $data['accountType'] = "银行固码";
        } else{
            $data['accountType'] = "银行卡";
        }

        return $this->accountBankCardsRepository->add($data);
    }

    /**
     * 根据用户id和表示id修改
     * @param int $id
     * @param int $uid
     * @param array $data
     * @return mixed
     */
    public function update(int $id, int $uid, array $data)
    {
        $data = array_except($data, ['_token']);
        if(isset( $data['bank_name'])){
            $bank = explode(",", $data['bank_name']);
            $data['bank_name'] = $bank[0];
            $data['bank_mark'] = $bank[1];
        }
        return $this->accountBankCardsRepository->update($id, $uid, $data);
    }

    /**
     * @param int $uid
     * @param int $status
     * @param string $bank_mark
     * @return mixed
     */
    public function getStatusAndUserIdAndBanKMark(int $uid, int $status, string $bank_mark)
    {
        return $this->accountBankCardsRepository->getStatusAndUserIdAndBanKMark($uid, $status, $bank_mark);
    }

    /**
     * @param int $uid
     * @param int $status
     * @param string $bank_mark
     * @return mixed
     */
    public function getStatusAndAccountTypeAndUserIdAndNotBanKMark(int $uid, int $status, string $bank_mark,$type)
    {
        return $this->accountBankCardsRepository->getStatusAndAccountTypeAndUserIdAndNotBanKMark($uid, $status, $bank_mark,$type);
    }

    /**
     * 跟据状态获取所有三方挂号
     * @param int $status
     * @param array $uid_arr
     * @param string $bank_mark
     * @return mixed
     */
    public function getStatusAndUidarrAndBanKMark(int $status, array $uid_arr, string $bank_mark)
    {
        return $this->accountBankCardsRepository->getStatusAndUidarrAndBanKMark($status, $uid_arr,$bank_mark);
    }

    /**
     * 跟据状态获取所有三方挂号
     * @param int $status
     * @param array $uid_arr
     * @param string $bank_mark
     * @return mixed
     */
    public function getStatusAndAccountTypeAndUidarrAndNotBanKMark(int $status, array $uid_arr, string $bank_mark,$type)
    {
        return $this->accountBankCardsRepository->getStatusAndAccountTypeAndUidarrAndNotBanKMark($status, $uid_arr,$bank_mark,$type);
    }

    /**
     * @param int $id
     * @param int $uid
     * @return mixed
     */
    public function findIdAndUserId(int $id, int $uid)
    {
        return $this->accountBankCardsRepository->findIdAndUserId($id, $uid);
    }

    /**
     * 根据手机标识和用户id获取卡号
     * @param string $phone_id
     * @param int $uid
     * @return mixed
     */
    public function findPhoneIdAndUserId(string $phone_id, int $uid)
    {
        return $this->accountBankCardsRepository->findPhoneIdAndUserId($phone_id, $uid);
    }

    /**
     * @param int $id
     * @param int $uid
     * @return mixed
     */
    public function del(int $id, int $uid)
    {
        return $this->accountBankCardsRepository->del($id, $uid);
    }


}