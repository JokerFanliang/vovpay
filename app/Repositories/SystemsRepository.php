<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/20
 * Time: 10:13
 */

namespace App\Repositories;

use App\Models\System;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SystemsRepository
{
    protected $system;
    public function __construct(System $system)
    {
        $this->system = $system;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findId(int $id)
    {
        return $this->system->whereId($id)->first();
    }

    /**安Name获取配置项值
     * @param string $name
     * @return mixed
     */
    static public function findKey(string $name)
    {
        //从缓存获取配置信息若没有在数据库中查找
        $systems=Cache::get('systems',function () {
            $system = DB::table('systems')->select('name','value')->get();
            $system_array = [];
            foreach ($system as $k=>$v){
                $system_array[$v->name] = $v;
            }
            return $system_array;
        });

        //缓存中指定配置不存在返回null
        return $systems[$name]->value??null;
    }

    /**
     * 添加
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->system->create($data);
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->system->get();
    }

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data)
    {
        return $this->system->whereId($id)->update($data);
    }
}