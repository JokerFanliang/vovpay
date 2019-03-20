<?php

namespace App\Services;

use App\Repositories\RoleRepository;

class RoleService
{
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * 获取所有
     * @return array
     */
    public function getAll()
    {
        return $this->roleRepository->getAll();
    }

    /**
     * 根据角色id获取菜单id
     * @param int $role_id
     * @return mixed
     */
    public function getRoleRules(int $role_id)
    {
        return $result = $this->roleRepository->getRoleRules($role_id);
    }


    /**
     * 添加
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->roleRepository->add($data);
    }

    /**
     * 根据id获取
     * @param string $id
     * @return array
     */
    public function findId(string $id)
    {
        $rule = $this->roleRepository->findId($id);
        return $rule->toArray();
    }

    /**
     * 更新
     * @param string $id
     * @param array $data
     * @return mixed
     */
    public function update(string $id, array $data)
    {
        $data = array_except($data, ['id','_token']);

        return $this->roleRepository->update($id, $data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        return $this->roleRepository->del($id);
    }

    /**
     * 同步关联更新角色权限
     * @param int $role_id
     * @param array|null $data
     * @return mixed
     */
    public function syncUpdateRoleRule(int $role_id, array $data = null)
    {
        return $this->roleRepository->syncUpdateRoleRule($role_id, $data);
    }
}