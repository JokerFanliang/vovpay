<?php

namespace App\Services;

use App\Repositories\UsersRepository;
use App\Exceptions\CustomServiceException;

class UserService
{
    protected $usersRepository;

    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    /**
     * 添加
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        // 去掉无用数据
        $data = array_except($data, ['id', '_token', 'password_confirmation']);

        // 用户标识等于代理商的时候,没有上级代理
        if ($data['groupType'] == '2' || $data['groupType'] == '3') {
            $data['parentId'] = 0;
            $data['agentName'] = '';
        }

        // 用户选择上级代理的时候，检测上级代理是否存在
        if ($data['parentId'] != '0') {
            $agent = $this->usersRepository->findIdAndGrouptype($data['parentId'], 2);
            if ($agent) {
                $data['agentName'] = $agent->username;
            } else {
                $data['parentId'] = 0;
                $data['agentName'] = '';
            }
        }
        $data['group_type'] = $data['groupType'];
        $data['password'] = bcrypt($data['password']);
        $data['payPassword'] = bcrypt('123456');
        $data['merchant'] = getMerchant(1); // 生成一个假的商户号
        $data['apiKey'] = md5(getOrderId());
        $data = array_except($data, 'groupType');
        $user = $this->usersRepository->add($data);

        if (!$user) {
            throw new CustomServiceException('会员添加失败！');
        }
        $merchant = getMerchant($user->id);
        $this->usersRepository->update($user->id, ['merchant' => $merchant]);

        // 添加的时候同步更新用户统计表
        $statistical['agent_id'] = $user->parentId;
        $this->usersRepository->saveUpdateUsersStatistical($user, $statistical);

        return $user;
    }

    /**
     * 根据id获取
     * @param int $id
     * @return array
     */
    public function findId(int $id)
    {
        return $this->usersRepository->findId($id);
    }

    /**
     * 根据username获取
     * @param string $username
     * @return mixed
     */
    public function findUser(string $username)
    {
        return $this->usersRepository->findUser($username);
    }



    /**
     * 根据商户号获取启用的商户
     * @param string $merchant
     * @return array
     */
    public function findMerchant(string $merchant)
    {
        return $this->usersRepository->findMerchant($merchant);
    }

    /**
     * 根据标识parentId获取用户分页
     * @param int $parentId
     * @param int $page
     * @return mixed
     */
    public function getParentIdPage(int $parentId, int $page)
    {
        return $this->usersRepository->getParentIdPage($parentId, $page);
    }

    /**
     * 更新
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data)
    {
        $data = array_except($data, ['id', '_token', 'password_confirmation']);
        $exists = $this->usersRepository->findIdPasswordExists($id, $data['password']);
        if ($exists) {
            $data = array_except($data, 'password');
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        // 用户标识等于代理商的时候,没有上级代理
        if ($data['groupType'] == '2' || $data['groupType'] == '3') {
            $data['parentId'] = 0;
            $data['agentName'] = '';
        }

        // 用户选择上级代理的时候，检测上级代理是否存在
        if ($data['parentId'] != '0') {
            $agent = $this->usersRepository->findIdAndGrouptype($data['parentId'], 2);
            if ($agent) {
                $data['agentName'] = $agent->username;
            } else {
                $data['parentId'] = 0;
                $data['agentName'] = '';
            }
        }else{
            $data['agentName'] = '';
        }
        $data['group_type'] = $data['groupType'];
        $data = array_except($data, 'groupType');

        return $this->usersRepository->update($id, $data);
    }

    /**
     * 更新密码
     * @param int $id
     * @param array $data
     * @return mixed
     */

    public function updatePassword(int $id, array $data)
    {
        $data = array_except($data, ['id', '_token', 'rpassword']);
        //检测ID 密码是否存在
        $exists = $this->usersRepository->contrastPassword($id, $data['password']);

        if ($exists) {
            $data['password'] = bcrypt($data['newPassword']);
            unset($data['newPassword']);
            return $this->usersRepository->update($id, $data);
        } else {
            return false;
        }

    }

    /**
     * 根据标识$group_id获取用户
     * @param int $group_id
     * @return mixed
     */
    public function getGroupAll(int $group_id)
    {
        return $this->usersRepository->getGroupAll($group_id);
    }

    /**
     * 状态变更
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateStatus(int $id, array $data)
    {
        //鉴权
        
        return $this->usersRepository->update($id, $data);
    }

    /**
     * 查询
     * @param array $data
     * @param int $page
     * @return mixed
     */
    public function searchPage(array $data, int $page)
    {
        return $this->usersRepository->searchPage($data, $page);
    }

    /**
     * 根据用户名和密码，状态获取用户信息
     * @param string $username
     * @param int $status
     * @return mixed
     */
    public function findUsernameAndStatus(string $username, int $status)
    {
        return $this->usersRepository->findUsernameAndStatus($username, $status);
    }

    /**
     * 伪删除
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        return $this->usersRepository->update($id, ['status' => 2]);
    }


    /**
     * 更新用户额度
     * @param int $uid
     * @param float $quota
     * @param int $type
     * @return mixed
     */
    public function userAddOrReduceQuota(int $uid,float $quota, int $type)
    {
        return $this->usersRepository->userAddOrReduceQuota($uid, $quota, $type);
    }

    public function getAllQuotaLargeAmount(int $status, float $amount)
    {
        return $this->usersRepository->getAllQuotaLargeAmount($status, $amount);
    }

    /**
     * 修改支付密码
     * @param int $uid
     * @param array $data
     * @return bool
     */
    public function editPayPassword(int $id,array $data){
        $data = array_except($data, ['id', '_token', 'rpassword']);
        //检测ID 密码是否存在
        $exists = $this->usersRepository->contrastPaypassword($id, $data['password']);

        if ($exists) {
            $up = array();
            $up['payPassword'] = bcrypt($data['newPassword']);
            return $this->usersRepository->update($id, $up);
        } else {
            return false;
        }
    }
    /**
     * 更新google密钥
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateGooleAuth(int $id, array $data)
    {
        return $this->usersRepository->update($id, $data);
    }

    /**检查用户是否配置google验证
     * @param string $username
     * @return bool
     */
    public function hasGoogleKey(string $username){

        $res=$this->usersRepository->getUserGoogleKey($username);
        return $res?true:false;
    }
}