<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/17
 * Time: 13:54
 */

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Repositories\BankCardRepository;

class BankCardService
{
    protected $bankCardRepository;

    public function __construct(BankCardRepository $bankCardRepository)
    {
        $this->bankCardRepository = $bankCardRepository;
    }

    /**
     * 添加
     * @param array $data
     * @return mixed
     */

    public function add(array $data)
    {
        $data['user_id']=Auth::user()->id;

        return $this->bankCardRepository->add($data);
    }

    /*
     * 得到该用户名下的所有银行卡
     * @param int $id
     */
    public function getUserIdAll(int $uid)
    {
        return $this->bankCardRepository->getUserIdAll($uid);
    }

    /**
     * 删除
     * @param int $id
     * @return mixed
     */

    public function destroy(int $id)
    {
        return $this->bankCardRepository->del($id);
    }

    /**
     * 获取一条数据
     * @param int $id
     * @return mixed
     */
    public function findId(int $id)
    {
        return $this->bankCardRepository->findId($id);
    }

    /**
     * 获取状态为1的银行卡
     * @param int $user_id
     * @return mixed
     */
    public function findStatus(int $user_id)
    {
        $list=$this->bankCardRepository->findStatus($user_id);
        if($list)
        {
            $list->bankCardNo=substr_replace($list->bankCardNo," **** **** **** ",3,12);
        }
        return $list;
    }

    /**
     * 编辑
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data)
    {
        $data = array_except($data, ['id', '_token', 'user_id']);
        $data['user_id']=Auth::user()->id;

        return $this->bankCardRepository->update($id, $data);
    }

    /**
     * 变更状态
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateStatus(int $id, array $data)
    {

        if ($this->bankCardRepository->findStatus($data['user_id'])) {

            $this->bankCardRepository->updateStatus($data['user_id'], ['status' => 0]);
        }

        return $this->bankCardRepository->update($id, $data);
    }

}