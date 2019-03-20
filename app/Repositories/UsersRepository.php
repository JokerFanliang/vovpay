<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/11/1
 * Time: 17:08
 */

namespace App\Repositories;

use App\Models\User;
use App\Models\Statistical;
use Illuminate\Support\Facades\Hash;

class UsersRepository
{

    protected $user;
    protected $statistical;

    /**
     * UsersRepository constructor.
     * @param User $user
     * @param Statistical $statistical
     */
    public function __construct(User $user, Statistical $statistical)
    {
        $this->user = $user;
        $this->statistical = $statistical;
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

        if (isset($data['group_type']) && $data['group_type']) {
            $sql .= ' and group_type = ?';
            $where['group_type'] = $data['group_type'];
        }

        if (isset($data['merchant']) && $data['merchant']) {
            $sql .= ' and merchant = ?';
            $where['merchant'] = $data['merchant'];
        }

        if (isset($data['username']) && $data['username']) {
            $sql .= ' and username = ?';
            $where['username'] = $data['username'];
        }

        if (isset($data['groupType']) && $data['groupType'] != '-1') {
            $sql .= ' and group_type = ?';
            $where['group_type'] = $data['groupType'];
        }

        if (isset($data['status']) && $data['status'] != '-1') {
            $sql .= ' and status = ?';
            $where['status'] = $data['status'];
        }
        if (isset($data['parentId']) && $data['parentId']) {
            $sql .= ' and parentId = ?';
            $where['parentId'] = $data['parentId'];
        }

        return $this->user->whereRaw($sql, $where)->orderBy('id', 'desc')->paginate($page);
    }

    /**
     * 根据用户身份标识获取
     * @param int $group_id
     * @param int $page
     * @return mixed
     */
    public function getGroupPage(int $group_id, int $page)
    {
        return $this->user->whereGroupType($group_id)->where('status', '<>', 2)->orderBy('id', 'desc')->paginate($page);
    }

    /**
     * 根据$parentId没有被删除的用户
     * @param int $parentId
     * @param int $page
     * @return mixed
     */
    public function getParentIdPage(int $parentId, int $page)
    {
        return $this->user->whereParentid($parentId)->where('status', '<>', 2)->orderBy('id', 'desc')->paginate($page);
    }

    /**
     * 根据用户身份标识获取，部分也
     * @param int $group_id
     * @return mixed
     */
    public function getGroupAll(int $group_id)
    {
        return $this->user->whereGroupType($group_id)->where('status', '<>', 2)->orderBy('id', 'desc')->get();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findId(int $id)
    {
        return $this->user->whereId($id)->first();
    }

    /**
     * @param string $username
     * @return mixed
     */
    public function findUser(string $username)
    {
        return $this->user->whereUsername($username)->first();
    }

    /**
     * 商户号
     * @param string $merchant
     * @return mixed
     */
    public function findMerchant(string $merchant)
    {
        return $this->user->whereMerchant($merchant)->first();
    }

    /**
     * 用户id和用户标识
     * @param int $id
     * @param int $group_type
     * @return mixed
     */
    public function findIdAndGrouptype(int $id, int $group_type)
    {
        return $this->user->whereId($id)->whereGroupType($group_type)->first();
    }

    /**
     * @param string $username
     * @param int $status
     * @return mixed
     */
    public function findUsernameAndStatus(string $username, int $status)
    {
        return $this->user->whereUsername($username)->whereStatus($status)->first();
    }

    /**
     * 检测唯一
     * @param string $field
     * @param string $value
     * @param int|null $id
     * @return mixed
     */
    public function searchCheck(string $field, string $value, int $id = null)
    {
        if ($id) {
            if ($field == 'email') {
                $sql = "id <> $id and email = ?";
                $where['email'] = $value;

            } else if ($field == 'username') {
                $sql = "id <> $id and username = ?";
                $where['username'] = $value;

            } else if ($field == 'phone') {
                $sql = "id <> $id and phone = ?";
                $where['phone'] = $value;
            }
        } else {
            if ($field == 'email') {
                $sql = " email = ?";
                $where['email'] = $value;

            } else if ($field == 'username') {

                $sql = " username = ?";
                $where['username'] = $value;

            } else if ($field == 'phone') {

                $sql = " phone = ?";
                $where['phone'] = $value;
            }
        }
        return $this->user->whereRaw($sql, $where)->first();
    }

    /**
     * 添加
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->user->create($data);
    }

    /**
     * 修改
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data)
    {
        return $this->user->whereId($id)->update($data);
    }

    /**
     * 删除
     * @param int $id
     * @return mixed
     */
    public function del(int $id)
    {
        return $this->user->whereId($id)->delete();
    }

    /**
     * 检测id和密码是否存在
     * @param string $password
     * @param int $id
     * @return mixed
     */
    public function findIdPasswordExists(int $id, string $password)
    {
        return $this->user->whereId($id)->wherePassword($password)->exists();
    }

    /**
     * 验证登录密码是否正确
     * @param int $id
     * @param int $password
     * @return bool
     */
    public function contrastPassword(int $id, string $password)
    {
        $result = $this->user->whereId($id)->first();
        $oldPassword = $result->password;
        return Hash::check($password, $oldPassword);
    }

    /**
     * 验证支付密码是否正确
     * @param int $id
     * @param int $password
     * @return bool
     */
    public function contrastPaypassword(int $id, String $password)
    {
        $result = $this->user->whereId($id)->first();
        $oldPassword = $result->payPassword;
        return Hash::check($password, $oldPassword);
    }


    /**
     * 同步更新用户统计表
     * @param User $user
     * @param array $data
     * @return mixed
     */
    public function saveUpdateUsersStatistical(User $user, array $data)
    {
        $statistical = $this->statistical;
        $statistical = new $statistical($data);
        return $user->Statistical()->save($statistical);
    }

    /**
     * 更新用户额度
     * @param int $uid
     * @param float $quota
     * @param int $type
     * @return mixed
     */
    public function userAddOrReduceQuota(int $uid, float $quota, int $type)
    {
        if ($type == 0) {
            return $this->user->whereId($uid)->increment('quota', $quota);
        } else {
            return $this->user->whereId($uid)->decrement('quota', $quota);
        }
    }

    /**
     * @param int $status
     * @param float $amount
     * @return mixed
     */
    public function getAllQuotaLargeAmount(int $status, float $amount)
    {
        return $this->user->whereStatus($status)->whereGroupType(3)->where('quota', '>=', $amount)->select('id')->get();
    }

    /**
     * @param string $username
     * @return mixed
     */
    public function getUserGoogleKey(string $username)
    {
        return $this->user->whereUsername($username)->value('google_key');
    }


}