<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/17
 * Time: 13:56
 */

namespace App\Repositories;


use App\Models\Bank;

class BanksRepository
{
    protected $banks;

    public function __construct(Bank $banks)
    {
        $this->banks=$banks;
    }







    /**
     * 获取一条数据
     * @param int $id
     * @return mixed
     */
    public function findId(int $id)
    {
        return $this->banks->whereId($id)->first();
    }

    /**获取所有银行
     * @return mixed
     */
    public function findStatus()
    {
        return $this->banks->whereStatus('1')->get();
    }



}