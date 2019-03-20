<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/11/1
 * Time: 17:08
 */

namespace App\Repositories;
use App\Models\Channel;

class ChannelsRepository
{

    protected $channel;

    /**
     * UsersRepository constructor.
     * @param Channel $channel
     */
    public function __construct( Channel $channel)
    {
        $this->channel = $channel;
    }

    /**
     * 获取所有，分页
     * @param int $page
     * @return mixed
     */
    public function getAllPage(int $page)
    {
        return $this->channel->orderBy('id', 'desc')->paginate($page);
    }

    /**
     * 添加
     * @param array $data
     * @return mixed
     */
    public function add(array $data){
        return $this->channel->create($data);
    }

    /**
     * 获取所有，不分页
     * @return mixed
     */
    public function getAll()
    {
        return $this->channel->orderBy('id', 'desc')->get();
    }

    /**
     * 根据id和状态获取
     * @param int $id
     * @param int $status
     * @return mixed
     */
    public function findIdAndStatus(int $id, int $status = 1)
    {
        return $this->channel->whereId($id)->whereStatus($status)->first();
    }

    /**
     * 修改
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data){
        return $this->channel->whereId($id)->update($data);
    }

    /**
     * 删除
     * @param int $id
     * @return mixed
     */
    public function del(int $id){
        return $this->channel->whereId($id)->delete();
    }

    /**
     * 根据id查询
     * @param string $id
     * @return mixed
     */
    public function findId(string $id)
    {
        return $this->channel->whereId($id)->first();
    }

    /**
     * 根据编码查询通道
     */
    public function findChannelCode(String $code){
        return $this->channel->whereChannelcode($code)->first();
    }
}