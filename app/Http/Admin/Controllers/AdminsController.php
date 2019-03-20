<?php

namespace App\Http\Admin\Controllers;

use App\Services\AdminsService;
use App\Services\CheckUniqueService;
use App\Services\RoleService;
use Illuminate\Http\Request;

class AdminsController extends Controller
{

    protected $adminsService;
    protected $checkUniqueService;
    protected $roleService;

    public function __construct( AdminsService $adminsService, CheckUniqueService $checkUniqueService, RoleService $roleService)
    {
        $this->adminsService      = $adminsService;
        $this->checkUniqueService = $checkUniqueService;
        $this->roleService        = $roleService;
    }

    /**
     * 列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $title = '管理员管理';

        $list = $this->adminsService->getAll();
        $role_list = $this->roleService->getAll();
        return view('Admin.Admins.index',compact('title', 'list','role_list'));
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
            'username'        => 'required|between:5,20|unique:admins,username,'.$id,
            'password'        => 'required|min:6|confirmed',
            'password_confirmation' => 'required|string',
            'email'           => 'required|unique:admins,email,'.$id,
            'phone'           => 'required|unique:admins,phone,'.$id,
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
            $result = $this->adminsService->update($request->id, $request->all());

            if($result)
            {
                return ajaxSuccess('修改成功！');
            }else{
                return ajaxError('修改失败！');
            }
        }else{
            $result = $this->adminsService->add($request->all());
            if($result)
            {
                return ajaxSuccess('添加角色成功！');
            }else{
                return ajaxError('添加角色失败！');
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
        $rule =$this->adminsService->findIdRole($id);
        return ajaxSuccess('获取成功',$rule);
    }

    /**
     * 状态变更
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveStatus(Request $request)
    {
        $data['status'] = $request->status == 'true' ? '1' : '0';

        $result = $this->adminsService->updateStatus($request->id, $data);

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
        $result = $this->checkUniqueService->check('admins', $request->type, $request->value, $request->id );

        if($result){
            return  response()->json(array('valid'=>true));
        }else{
            return  response()->json(array('valid'=>false));
        }
    }

    /**
     * 删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $result = $this->adminsService->destroy($request->id);
        if($result)
        {
            return ajaxSuccess('删除成功！');
        }else{
            return ajaxError('删除失败！');
        }
    }

    /**检查用户是否配置google验证
     * @param Request $request
     */
    public function hasGoogleKey(Request $request){

        $result=$this->adminsService->hasGoogleKey($request->username);

        if($result)
        {
            return ajaxSuccess('用户已配置google_key');
        }else{
            return ajaxError('用户未配置google_key！');
        }
    }

}
