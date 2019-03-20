<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/20
 * Time: 10:12
 */

namespace App\Services;

use App\Repositories\SystemsRepository;

class SystemsService
{
    protected $systemsRepository;

    /**
     * SystemsService constructor.
     * @param SystemsRepository $systemsRepository
     */
    public function __construct(SystemsRepository $systemsRepository)
    {
        $this->systemsRepository = $systemsRepository;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        // 去掉无用数据
        $data = array_except($data, ['id', '_token']);
        return $this->systemsRepository->add($data);
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->systemsRepository->getAll();
    }

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data)
    {
        $data = array_except($data, ['id', '_token']);
        return $this->systemsRepository->update($id,$data);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findId(int $id)
    {
        return $this->systemsRepository->findId($id);
    }


    /**获取系统配置项
     * @param $name
     */
   public function findKey($name){
       return $this->systemsRepository->findKey($name);
    }
}