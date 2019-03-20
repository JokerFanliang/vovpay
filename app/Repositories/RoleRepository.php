<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository
{
    protected $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    /**
     * 获取所有
     * @return mixed
     */
    public function getAll()
    {
        return $this->role->get();
    }

    /**
     * 根据角色获取菜单id
     * @param int $role_id
     * @return array
     */
    public function getRoleRules(int $role_id)
    {
        return $this->role->find($role_id)->rules()->pluck('rules.id')->toArray();
    }

    /**
     * 同步关联更新角色权限
     * @param int $role_id
     * @param array|null $data
     * @return mixed
     */
    public function syncUpdateRoleRule( int $role_id ,array $data = null)
    {
        return $this->findId($role_id)->rules()->sync($data);
    }

    /**
     * 添加
     * @param array $data
     * @return mixed
     */
    public function add(array $data){
        return $this->role->create($data);
    }

    /**
     * 修改
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data){
        return $this->role->whereId($id)->update($data);
    }

    /**
     * 删除
     * @param int $id
     * @return mixed
     */
    public function del(int $id){
        return $this->role->whereId($id)->delete();
    }

    /**
     * 修改状态--是否验证
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateCheck(int $id , array $data)
    {
        return $this->role->whereId($id)->update($data);
    }

    /**
     * 根据id查询
     * @param int $id
     * @return mixed
     */
    public function findId(int $id)
    {
        return $this->role->find($id);
    }

}