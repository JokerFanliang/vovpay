<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/20
 * Time: 10:13
 */

namespace App\Repositories;


use App\Models\Withdraw;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WithdrawsRepository
{
    protected $withdraw;

    public function __construct(Withdraw $withdraw)
    {
        $this->withdraw = $withdraw;
    }

    /**
     * 添加
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->withdraw->create($data);
    }

    /**
     * 查询带分页
     * @param string $sql
     * @param array $where
     * @param int $page
     * @return mixed
     */
    public function searchPage($sql,$where,int $page)
    {
//        $user_id = Auth::user()->id;
//        $sql = " user_id = {$user_id} ";
//        $where = [];

        return $this->withdraw->whereRaw($sql, $where)->orderBy('id', 'desc')->paginate($page);
    }

    /**获取所有结算记录
     * @return mixed
     */
    public function getAllPage(int $page)
    {
        $user_id = Auth::user()->id;
        $sql = " user_id = {$user_id} ";
        $where = [];
        return $this->withdraw->whereRaw($sql, $where)->orderBy('id', 'desc')->paginate($page);
    }


    /**获取所有结算统计数据
 * @return mixed
 */
    public function searchWithdrawInfoSum($sql, $where, $page)
    {

        return $this->withdraw
            ->select(DB::raw('sum(withdrawAmount) as amountSum ,count(id) as withdrawCount,sum(withdrawRate) as withdrawRateSum, sum(toAmount) as toAmountSum'))
            ->whereRaw($sql, $where)->get()->toArray();;

    }

    /**更新结算记录
     * @param $orderId
     * @param $data
     */
    public function update($id,$data){
        $sql = " id = {$id} ";
        $where = [];
        return $this->withdraw->whereRaw($sql, $where)->update($data);
    }

    /**根据id获取结算记录
     * @param $id
     * @return mixed
     */
    public function findById($id){

        return $this->withdraw->where('id',$id)->first();
    }

    /**
     * 获取为处理的结算
     * return mixed
     */
    public function getCount(int $status = 0)
    {
        return $this->withdraw->whereStatus($status)->count();
    }


}