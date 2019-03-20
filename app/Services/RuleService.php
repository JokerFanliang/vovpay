<?php

namespace App\Services;

use App\Repositories\RuleRepository;
use App\Exceptions\CustomServiceException;

class RuleService
{
    protected $ruleRepository;

    public function __construct(RuleRepository $ruleRepository)
    {
        $this->ruleRepository = $ruleRepository;
    }

    /**
     * 获取所有菜单，生成树状
     * @return array
     */
    public function getAll()
    {
        $result = $this->ruleRepository->getAll();
        return tree($result);
    }

    /**
     * 获取所有菜单的指定字段
     * @return array
     */
    public function getRuleListField()
    {
        return $this->ruleRepository->getListField()->toArray();
    }

    /**
     * 是否验证
     * @param string $id
     * @param string $status
     * @return mixed
     */
    public function updateCheck(string $id, string $status)
    {
        $data['is_check'] = $status;
        return $this->ruleRepository->updateCheck($id,$data);
    }

    /**
     * 菜单添加
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        if($data['pid'] != '0')
        {
            $exists = $this->ruleRepository->findId($data['pid']);
            if( !$exists)
            {
                throw new CustomServiceException('父级菜单不存在！');
            }
            $data['level'] = $exists->level + 1;
        }else{
            $data['level'] = 1;
        }

        return $this->ruleRepository->add($data);
    }

    /**
     * 根据id获取
     * @param string $id
     * @return array
     */
    public function findId(string $id)
    {
        $rule = $this->ruleRepository->findId($id);
        return $rule->toArray();
    }

    public function update(string $id, array $data)
    {

        if($data['pid'] != '0')
        {
            $exists = $this->ruleRepository->findId($data['pid']);
            if( !$exists)
            {
                throw new CustomServiceException('父级菜单不存在！');
            }
            $data['level'] = $exists->level + 1;
        }else{
            $data['level'] = 1;
        }

        $data = array_except($data, ['id','_token']);

        return $this->ruleRepository->update($id, $data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        return $this->ruleRepository->del($id);
    }
}