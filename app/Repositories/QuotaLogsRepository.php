<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/11/1
 * Time: 17:08
 */
namespace App\Repositories;

use App\Models\Quota_log;
use Carbon\Carbon;

class QuotaLogsRepository
{
    protected $quota_log;

    /**
     * UsersRepository constructor.
     * @param Quota_log $quota_log
     */
    public function __construct(Quota_log $quota_log)
    {
        $this->quota_log = $quota_log;
    }

    /**
     * 查询带分页
     * @param array $data
     * @param int $page
     * @return mixed
     */
    public function searchPage(array $data, int $page)
    {
        $sql = ' 1=1 ';
        $where = [];

        if(isset($data['user_id']) && $data['user_id'])
        {
            $sql .= ' and user_id = ?';
            $where['user_id'] = $data['user_id'];
        }
        if (isset($data['searchTime']) && $data['searchTime']) {
            $time = explode(" - ", $data['searchTime']);
            $sql .= ' and updated_at >= ?';
            $where['updated_at1'] = $time[0];
            $sql .= ' and updated_at <= ?';
            $where['updated_at2'] = $time[1];

        }else{
            $today = Carbon:: today()->toDateTimeString();
            $sql .= ' and updated_at >= ?';
            $where['updated_at1'] = $today;
            $now = Carbon::now()->toDateTimeString();
            $sql .= ' and updated_at <= ?';
            $where['updated_at2'] = $now;
        }

        return $this->quota_log->whereRaw($sql, $where)->orderBy('id', 'desc')->paginate($page);
    }
    /**
     * 查询增加统计
     * @param array $data
     * @param int $quota_type
     * @return mixed
     */
    public function searchNum(array $data, int $quota_type)
    {

        $sql = 'quota_type = '. $quota_type;

        if(isset($data['user_id']) && $data['user_id'])
        {
            $sql .= ' and user_id = ?';
            $where['user_id'] = $data['user_id'];
        }
        if (isset($data['searchTime']) && $data['searchTime']) {
            $time = explode(" - ", $data['searchTime']);
            $sql .= ' and updated_at >= ?';
            $where['updated_at1'] = $time[0];
            $sql .= ' and updated_at <= ?';
            $where['updated_at2'] = $time[1];

        }else{
            $today = Carbon:: today()->toDateTimeString();
            $sql .= ' and updated_at >= ?';
            $where['updated_at1'] = $today;
            $now = Carbon::now()->toDateTimeString();
            $sql .= ' and updated_at <= ?';
            $where['updated_at2'] = $now;
        }

        return $this->quota_log->whereRaw($sql, $where)->sum('quota');
    }

    /**
     * 添加
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->quota_log->create($data);
    }
}