<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/12/21
 * Time: 17:12
 */

namespace App\Services;


use App\Repositories\AccountPhoneRepository;
use Illuminate\Support\Facades\Auth;

class AccountPhoneService
{
    protected $accountPhoneRepository;
    /**
     * AccountPhoneService constructor.
     * @param AccountPhoneRepository $accountPhoneRepository
     */
    public function __construct(AccountPhoneRepository $accountPhoneRepository)
    {
        $this->accountPhoneRepository = $accountPhoneRepository;
    }
    /**
     * 带分页统计信息查询
     * @param array $data
     * @param int $page
     * @return mixed
     */
    public function searchPhoneStastic(array $data, int $page)
    {
        $params = $this->buildSearchSql($data);
        return $this->accountPhoneRepository->searchPage($params['sql'],$params['where'],$params['time'], $page);
    }
    /**
     * 获取所有监听设备
     * @param array $data
     * @param int $page
     * @return mixed
     */
    public function searchPhone(array $data,int $page)
    {
        $params = $this->buildSearchSql($data);
        return $this->accountPhoneRepository->searchPhone($params['sql'],$params['where'], $page);
    }

    /**构建设备查询条件
     * @param array $data
     * @return array
     */
    private function buildSearchSql(array $data){
        $sql = ' 1=1 ';
        $where = [];

        if (isset($data['account']) && $data['account']) {
            $sql .= 'and pay_account_phones.account = ?';
            $where['account'] = $data['account'];
        }
        if (isset($data['user_id']) && $data['user_id']) {
            $sql .= ' and pay_account_phones.user_id = ?';
            $where['user_id'] = $data['user_id'];
        }
        if (isset($data['accountType']) && $data['accountType']!='-1') {
            if ($data['accountType'] == 'alipay') {
                $data['accountType'] = '支付宝';
            } elseif ($data['accountType'] == 'wechat') {
                $data['accountType'] = '微信';
            } elseif ($data['accountType'] == 'cloudpay') {
                $data['accountType'] = '云闪付';
            }
            $sql .= ' and accountType = ?';
            $where['accountType'] = $data['accountType'];
        }
        if (isset($data['third']) && $data['third'] == 1) {
            $sql .= ' and third = ?';
            $where['third'] = $data['third'];
        }

        $params = [
            'sql'   => $sql,
            'where' => $where,
            'time'  => isset($data['searchTime']) ? $data['searchTime'] : ''
        ];
        return $params;

    }



    /**
     * 添加
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $data = array_except($data, ['_token']);
        $data['qrcode']= $data['qrcode']?:'0';
        if (isset($data['accountType']) && $data['accountType'] == 'cloudpay'){
            $data['accountType'] = "云闪付";
        } elseif (isset($data['alipayusername']) && isset($data['alipayuserid'])) {
            $data['accountType'] = "支付宝";
        } else{
            $data['accountType'] = "微信";
        }

        return $this->accountPhoneRepository->add($data);
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

        if( isset($data['accountType']) && $data['accountType'] == 'cloudpay' )
        {
            $data['accountType'] = "云闪付";
        }


        return $this->accountPhoneRepository->update($id, $uid, $data);
    }

    /**
     * @param int $id
     * @param int $uid
     * @return mixed
     */
    public function findIdAndUserId(int $id, int $uid)
    {
        return $this->accountPhoneRepository->findIdAndUserId($id,$uid);
    }

    /**
     * @param int $id
     * @param int $uid
     * @return mixed
     */
    public function del(int $id, int $uid)
    {
        return $this->accountPhoneRepository->del($id,$uid);
    }

    /**
     * @param string $type
     * @param int $uid
     * @param int $status
     * @return mixed
     */
    public function getStatusAndAccountType( string $type,int $uid, int $status){
        return $this->accountPhoneRepository->getStatusAndAccountType($type, $uid, $status);
    }


    /**
     * @param string $type
     * @param int $uid
     * @param int $status
     * @return mixed
     */
    public function getStatusAndAccountTypeAndSolidcode( string $type,int $uid, int $status){
        return $this->accountPhoneRepository->getStatusAndAccountTypeAndSolidcode($type, $uid, $status);
    }

    /**
     * 根据状态、类型、用户id获取账号
     * @param string $type
     * @param int $status
     * @param array $uid_arr
     * @return mixed
     */
    public function getStatusAndAccountTypeAndUidarr(string $type, int $status,array $uid_arr)
    {
        return $this->accountPhoneRepository->getStatusAndAccountTypeAndUidarr($type,$status,$uid_arr);
    }

    /**
     * 根据状态、类型、用户id获取账号
     * @param string $type
     * @param int $status
     * @param array $uid_arr
     * @return mixed
     */
    public function getStatusAndAccountTypeAndSolidcodeAndUidarr(string $type, int $status,array $uid_arr)
    {
        return $this->accountPhoneRepository->getStatusAndAccountTypeAndSolidcodeAndUidarr($type,$status,$uid_arr);
    }

    /**
     * @param string $phoneid
     * @param int $status
     * @param int $uid
     * @return mixed
     */
    public function getPhoneidAndStatusAndUserid(string $phoneid, int $status, int $uid )
    {
        return $this->accountPhoneRepository->getPhoneidAndStatusAndUserid($phoneid, $status, $uid);
    }

    /**收款设备操作鉴权
     * @param $user_id
     * @param $device_id
     */
    public function ownerAuthorize($user_id,$device_id){
        $deviceInfo=$this->accountPhoneRepository->findIdAndUserId($device_id,$user_id);
        if(!$deviceInfo){
            throw new CustomServiceException('非法操作!');
        }
    }
}