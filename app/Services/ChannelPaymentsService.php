<?php

namespace App\Services;

use App\Repositories\ChannelPaymentsRepository;

class ChannelPaymentsService
{
    protected $channelPaymentsRepository;

    public function __construct(ChannelPaymentsRepository $channelPaymentsRepository)
    {
        $this->channelPaymentsRepository = $channelPaymentsRepository;
    }

    /**
     * 添加
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        // 去掉无用数据
        $data['ico'] = $data['fileIco'];
        $data = array_except($data, ['id','_token','fileIco']);

        return $this->channelPaymentsRepository->add($data);
    }

    /**
     * 根据id获取
     * @param string $id
     * @return array
     */
    public function findId(string $id)
    {
        $channels = $this->channelPaymentsRepository->findId($id);
        return $channels->toArray();
    }

    /**
     *
     * @param int $page
     * @return mixed
     */
    public function getAllPage(int $page)
    {
        return $this->channelPaymentsRepository->getAllPage($page);
    }

    /**
     * 根据编码
     * @param string $code
     * @return mixed
     */
    public function findPaymentCode(string $code)
    {
        return $this->channelPaymentsRepository->findPaymentCode($code);
    }

    /**
     * 更新
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data)
    {
        $data['ico'] = $data['fileIco'];
        $data = array_except($data, ['id','_token','fileIco']);

        return $this->channelPaymentsRepository->update($id, $data);
    }

    /**
     * 获取所有不带分页
     * @return mixed
     */
    public function getAll()
    {
        return $this->channelPaymentsRepository->getAll();
    }

    /**
     * 根据状态获取所有
     * @param int $status
     * @return mixed
     */
    public function getStatusAll(int $status)
    {
        return $this->channelPaymentsRepository->getStatusAll($status);
    }

    /**
     * 状态变更
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateStatus(int $id, array $data){

        return $this->channelPaymentsRepository->update($id, $data);
    }


    /**
     * 伪删除
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        return $this->channelPaymentsRepository->del($id);
    }

    /**
     * 根据ID获取通道
     * @param int $id
     * @return mixed
     */
    public function channelpay(int $id)
    {
       return $this->channelPaymentsRepository->channelpay($id);
    }
}