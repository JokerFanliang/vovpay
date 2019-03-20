<?php

namespace App\Repositories;

use App\Models\Rule;

class RuleRepository
{
    protected $rule;

    public function __construct(Rule $rule)
    {
        $this->rule = $rule;
    }

    /**
     * 获取所有
     * @return mixed
     */
    public function getAll()
    {
        return $this->rule->orderBy('sort', 'desc')->get();
    }

    /**
     * 获取所有指定字段
     * @return mixed
     */
    public function getListField()
    {
        return $this->rule->select('id', 'title', 'pid')->orderBy('sort', 'desc')->get();
    }

    /**
     * 添加
     * @param array $data
     * @return mixed
     */
    public function add(array $data){
        return $this->rule->create($data);
    }

    /**
     * 修改
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data){
        return $this->rule->whereId($id)->update($data);
    }

    /**
     * 删除
     * @param int $id
     * @return mixed
     */
    public function del(int $id){
        return $this->rule->whereId($id)->delete();
    }

    /**
     * 修改状态--是否验证
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateCheck(int $id , array $data)
    {
        return $this->rule->whereId($id)->update($data);
    }

    /**
     * 根据id查询
     * @param int $id
     * @return mixed
     */
    public function findId(int $id)
    {
        return $this->rule->find($id);
    }

}