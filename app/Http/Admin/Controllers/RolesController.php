<?php

namespace App\Http\Admin\Controllers;

use App\Services\RoleService;
use App\Services\RuleService;
use Illuminate\Http\Request;

class RolesController extends Controller
{

    protected $roleService;
    protected $ruleService;

    public function __construct( RoleService $roleService , RuleService $ruleService)
    {
        $this->roleService = $roleService;
        $this->ruleService = $ruleService;
    }

    public function index()
    {
        $title = '角色管理';

        $list = $this->roleService->getAll();
        return view('Admin.Role.index',compact('title', 'list'));
    }

    /**
     * 编辑
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $rule =$this->roleService->findId($id);
        return ajaxSuccess('获取成功',$rule);
    }

    /**
     * 添加
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
        ],[
            'name.required' => '角色名不能为空',
        ]);

        // id 存在更新。不存在添加
        if($request->id)
        {
            $result = $this->roleService->update($request->id, $request->all());

            if($result)
            {
                return ajaxSuccess('修改成功！');
            }else{
                return ajaxError('修改失败！');
            }
        }else{
            $result = $this->roleService->add($request->all());
            if($result)
            {
                return ajaxSuccess('添加角色成功！');
            }else{
                return ajaxError('添加角色失败！');
            }
        }
    }


    /**
     * 删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $result = $this->roleService->destroy($request->id);
        if($result)
        {
            return ajaxSuccess('删除成功！');
        }else{
            return ajaxError('删除失败！');
        }
    }


    /**
     * 角色权限列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getRules(Request $request)
    {
        $title = '权限分配';
        $role_rules_arr = $this->roleService->getRoleRules($request->role_id);
        $all_rules = $this->ruleService->getRuleListField();
        $rules = ztreeData($all_rules, $role_rules_arr);
        $rules[] = [
            "id"=>0,
            "pid"=>0,
            "title"=>"全部",
            "open"=>true
        ];
        return view('Admin.Role.setRules',['rules' => json_encode($rules), 'role_id' => $request->role_id, 'title'=>$title]);
    }


    /**
     * 权限分配
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeRules(Request $request)
    {
        $result = $this->roleService->syncUpdateRoleRule($request->role_id, $request->rules);
        if($result)
        {
            return ajaxSuccess('配置成功！');
        }else{
            return ajaxError('配置失败！');
        }
    }

}
