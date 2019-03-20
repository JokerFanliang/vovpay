<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/11/1
 * Time: 17:08
 */

namespace App\Repositories;
use App\Models\Channel_payment;

class ChannelPaymentsRepository
{

    protected $channel_payment;

    /**
     * UsersRepository constructor.
     * @param Channel_payment $channel_payment
     */
    public function __construct( Channel_payment $channel_payment)
    {
        $this->channel_payment = $channel_payment;
    }

    /**
     * 获取所有带分页
     * @param int $page
     * @return mixed
     */
    public function getAllPage(int $page)
    {
        return $this->channel_payment->orderBy('id', 'desc')->paginate($page);
    }

    /**
     * 根据支付编码获取启用的支付方式
     * @param string $code
     * @return mixed
     */
    public function findPaymentCode(string $code)
    {
        $sql   = 'paymentCode=? and status=1';
        $where['paymentCode'] = $code;
        return $this->channel_payment->whereRaw($sql, $where)->first();
    }

    /**
     * 添加
     * @param array $data
     * @return mixed
     */
    public function add(array $data){
        return $this->channel_payment->create($data);
    }

    /**
     * 获取所有
     * @return mixed
     */
    public function getAll()
    {
        return $this->channel_payment->orderBy('id', 'desc')->get();
    }

    /**
     * 根据状态获取所有
     * @param int $status
     * @return mixed
     */
    public function getStatusAll(int $status)
    {
        return $this->channel_payment->whereStatus($status)->orderBy('id', 'desc')->get();
    }

    /**
     * 修改
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data){
        return $this->channel_payment->whereId($id)->update($data);
    }

    /**
     * 删除
     * @param int $id
     * @return mixed
     */
    public function del(int $id){
        return $this->channel_payment->whereId($id)->delete();
    }

    /**
     * 根据id查询
     * @param string $id
     * @return mixed
     */
    public function findId(string $id)
    {
        return $this->channel_payment->find($id);
    }

    /**
     *  根据id查询开启的支付方式
     * @param int $id
     * @return mixed
     */
    public function channelpay(int $id)
    {
        return $this->channel_payment->whereId($id)->whereStatus(1)->first();
    }
}