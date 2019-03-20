<?php

namespace App\Services;

use App\Repositories\AdminsRepository;

class AdminsService
{
    protected $adminsRepository;

    public function __construct(AdminsRepository $adminsRepository)
    {
        $this->adminsRepository = $adminsRepository;
    }

    /**
     * 所有
     * @return mixed
     */
    public function getAll()
    {
        return $this->adminsRepository->getAll();
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
        $exists = $this->adminsRepository->findIdPasswordExists($id, $data['password']);
        if ($exists) {
            $data = array_except($data, 'password');
        } else {
            $data['password'] = bcrypt($data['password']);
        }
        $this->adminsRepository->syncUpdateAdminsRole($id, [$data['role_id']]);

        $data = array_except($data, 'role_id');
        return $this->adminsRepository->update($id, $data);
    }

    /**
     * 状态变更
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateStatus(int $id, array $data)
    {
        return $this->adminsRepository->update($id, $data);
    }

    /**
     * 添加
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $role_id = $data['role_id'];
        $data = array_except($data, ['password_confirmation', 'role_id']);
        $data['password'] = bcrypt($data['password']);

        $admins = $this->adminsRepository->add($data);
        $this->adminsRepository->syncUpdateAdminsRole($admins->id, [$role_id]);
        return $admins;
    }

    /**
     * 根据id获取
     * @param int $id
     * @return array
     */
    public function findIdRole(int $id)
    {
        $rule = $this->adminsRepository->findId($id);
        $rule_array = $rule->toArray();
        $roles_id_array = $rule->roles()->pluck('roles.id')->toArray();
        $rule_array['role_id'] = $roles_id_array[0];
        return $rule_array;
    }

    /**
     * @param string $username
     * @param int $status
     * @return mixed
     */
    public function findUsernameAndStatus(string $username, int $status)
    {
        return $this->adminsRepository->findUsernameAndStatus($username, $status);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $this->adminsRepository->syncUpdateAdminsRole($id, []);
        return $this->adminsRepository->del($id);
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
        $exists = $this->adminsRepository->contrastPassword($id, $data['password']);
        if ($exists) {
            $data['password'] = bcrypt($data['newPassword']);
            unset($data['newPassword']);
            return $this->adminsRepository->update($id, $data);
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
        return $this->adminsRepository->update($id, $data);
    }


    /**检查用户是否配置google验证
     * @param string $username
     * @return bool
     */
    public function hasGoogleKey(string $username)
    {
        $res=$this->adminsRepository->getAdminGoogleKey($username);
        return $res?true:false;
    }
}