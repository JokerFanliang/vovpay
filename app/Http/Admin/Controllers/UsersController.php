<?php

namespace App\Http\Admin\Controllers;

use App\Services\CheckUniqueService;
use App\Services\QuotalogService;
use App\Services\StatisticalService;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Services\UserRateService;

class UsersController extends Controller
{
    protected $userService;
    protected $checkUniqueService;
    protected $userRateService;
    protected $quotalogService;
    protected $statisticalService;

    public function __construct(StatisticalService $statisticalService, UserService $userService, CheckUniqueService $checkUniqueService, UserRateService $userRateService, QuotalogService $quotalogService)
    {
        $this->userService        = $userService;
        $this->checkUniqueService = $checkUniqueService;
        $this->userRateService    = $userRateService;
        $this->quotalogService    = $quotalogService;
        $this->statisticalService = $statisticalService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {

        $data = $request->input();
        if ($request->type == 'user') {
            $data['group_type'] = 1;
            $title = '普通商户';

        } elseif ($request->type == 'agent') {
            $title = '代理商户';
            $data['group_type'] = 2;

        } elseif ($request->type == 'court'){
            $title = '场外商户';
            $data['group_type'] = 3;
        }
        $list = $this->userService->searchPage($data, 10);
        $agent_list = $this->userService->getGroupAll(2);
        $query = $request->query();
        return view("Admin.Users.{$request->type}",compact('title', 'list', 'query', 'agent_list'));
    }

    /**
     * 添加
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $id = $request->id ? $request->id : '';
        $this->validate($request, [
            'username'        => 'required|between:5,20|unique:users,username,'.$id,
            'password'        => 'required|min:6|confirmed',
            'password_confirmation' => 'required|string',
            'email'           => 'required|unique:users,email,'.$id,
            'phone'           => 'required|unique:users,phone,'.$id,
        ],[
            'username.required'         => '用户名不能为空',
            'username.unique'           => '用户名已存在',
            'username.between'          => '用户名长度5~20个字符!',
            'password.required'         => '密码不能为空',
            'password.between'          => '密码最小长度6个字符!',
            'password.confirmed'        => '两次输入的密码不一致',
            'password_confirmation.required'  => '确认密码不能为空',
            'email.required'            => '邮箱不能为空',
            'email.unique'              => '邮箱已存在',
            'phone.required'            => '电话不能为空',
            'phone.unique'              => '电话已存在',
        ]);

        // id 存在更新。不存在添加
        if($id)
        {
            $result = $this->userService->update($request->id, $request->all());

            if($result)
            {
                return ajaxSuccess('修改成功！');
            }else{
                return ajaxError('修改失败！');
            }
        }else{
            $result = $this->userService->add($request->all());
            if($result)
            {
                return ajaxSuccess('添加会员成功！');
            }else{
                return ajaxError('添加会员失败！');
            }
        }
    }

    /**
     * 编辑
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $rule =$this->userService->findId($id);
        return ajaxSuccess('获取成功',$rule->toArray());
    }

    /**
     * 状态变更
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveStatus(Request $request)
    {
        $data['status'] = $request->status == 'true' ? '1' : '0';

        $result = $this->userService->updateStatus($request->id, $data);

        if($result)
        {
            return ajaxSuccess('修改成功！');
        }else{
            return ajaxError('修改失败！');
        }
    }

    /**
     * 唯一验证
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkUnique(Request $request)
    {
        $result = $this->checkUniqueService->check('users', $request->type, $request->value, $request->id);

        if($result){
            return  response()->json(array("valid"=>"true"));
        }else{
            return  response()->json(array("valid"=>"false"));
        }
    }

    /**
     * 删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $result = $this->userService->destroy($request->id);
        if($result)
        {
            return ajaxSuccess('删除成功！');
        }else{
            return ajaxError('删除失败！');
        }
    }

    /**
     * 用户费率配置
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function channel(Request $request)
    {
        $title = '用户通道设置';
        $uid   = $request->id;
        $list  = $this->userRateService->getFindUidRate($uid);
        return view('Admin.Users.channel',compact('title','list', 'uid'));
    }

    /**
     * 用户费率状态修改
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveUserRateStatus(Request $request)
    {
        $uid    = $request->id;
        $payId  = $request->payId;
        $status = $request->status;
        $channelId = $request->channelId;

        $result = $this->userRateService->saveStatus($uid, $payId, $status, $channelId);
        if($result)
        {
            return ajaxSuccess('修改成功！');
        }else{
            return ajaxError('修改失败！');
        }
    }

    /**
     * 获取用户单条费率，不存在初始化
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserRate(Request $request)
    {
        $uid    = $request->id;
        $payId  = $request->payId;
        $channelId = $request->channelId;
        $result = $this->userRateService->getFindUidPayId($uid, $payId);
        if($result)
        {
           return ajaxSuccess('',$result->toArray());
        }else{
            $data['channel_id'] = $channelId;
            $data['channel_payment_id'] = $payId;
            $data['status'] = 0;
            $data['rate']   = 0;
            return ajaxSuccess('',$data);
        }
    }

    /**
     * 用户费率更新添加
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userRateStore(Request $request)
    {
        $uid    = $request->id;
        $payId  = $request->payId;
        $channelId = $request->channelId;
        $rate   = $request->rate;
        $status = $request->status;
        $result = $this->userRateService->userRateStore($uid, $channelId, $rate, $payId, $status);
        if($result)
        {
            return ajaxSuccess('修改成功！');
        }else{
            return ajaxError('修改失败！');
        }
    }

    /**
     * 获取当前用户额度
     * @param Request $request
     * @return mixed
     */
    public function quota(Request $request)
    {
        $uid  = $request->id;
        $user = $this->userService->findId($uid);
        if( !$user || $user->group_type != 3){
            return ajaxError('无权获取');
        }

        return ajaxSuccess('获取成功',['quota'=>$user->quota]);
    }

    /**
     * 场外用户额度提交
     * @param Request $request
     * @return mixed
     */
    public function quotaStore(Request $request)
    {
        $uid  = $request->quota_id;
        $user = $this->userService->findId($uid);
        if( !$user || $user->group_type != 3){
            return ajaxError('无权操作！');
        }
        $result = '';
        if($request->quota <= 0)
        {
            return ajaxError('分数错误！');
        }

        if($request->quota_type == 0)
        {
            $result = $this->userService->userAddOrReduceQuota($uid,$request->quota,0);
        }else if($request->quota_type == 1){
            if($user->quota < $request->quota) $request->quota = $user->quota;
            $result = $this->userService->userAddOrReduceQuota($uid,$request->quota,1);
        }else{
            return ajaxError('操作错误！');
        }

        if(!$result)
        {
            return ajaxError('添加失败！');
        }
        $data = [
            'user_id' => $uid,
            'quota'   => $request->quota,
            'quota_type' => $request->quota_type,
        ];
        $this->quotalogService->add($data);
        return ajaxSuccess('添加成功！');
    }

    /**
     * 场外商户上分记录
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function quotaLog(Request $request)
    {
        $query = $request->query();
        $title = '商户分数记录';
        $uid   = $request->id;
        $query['user_id']    = $request->id;
        $list = $this->quotalogService->searchPage($query,10);
        $data['add_num']     = $this->quotalogService->searchNum($query,0);
        $data['reduce_num']  = $this->quotalogService->searchNum($query,1);
        return view('Admin.Users.quota_log',compact('list','title','uid','query','data'));
    }

    /**
     * 用户余额加减
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function balance(Request $request)
    {
        $data = $request->input();
        $user = $this->userService->findId($data['uid']);
        if(!$user)
        {
            return ajaxError('商户不存在！');
        }
        // 增加
        if($data['balance_type'] == 0)
        {
            $request = $this->statisticalService->updateUseridHandlingFeeBalanceIncrement($data['uid'],$data['amount']);
        }

        // 减少
        if($data['balance_type'] == 1)
        {
            $request = $this->statisticalService->updateUseridHandlingFeeBalanceDecrement($data['uid'],$data['amount']);
        }
        if($request){
            return ajaxSuccess('修改成功');
        }else{
            return ajaxSuccess('修改失败');
        }
    }
}
