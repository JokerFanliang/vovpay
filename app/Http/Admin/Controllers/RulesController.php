<?php

namespace App\Http\Admin\Controllers;

use App\Services\RuleService;
use Illuminate\Http\Request;

class RulesController extends Controller
{

    protected $ruleService;

    public function __construct( RuleService $ruleService )
    {
        $this->ruleService = $ruleService;
    }

    public function index()
    {
        $title = '菜单管理';

        $list = $this->ruleService->getAll();
        return view('Admin.Rule.index',compact('title', 'list'));
    }

    /**
     * 获取需要编辑的菜单
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $rule =$this->ruleService->findId($id);
        return ajaxSuccess('获取成功',$rule);
    }

    /**
     * 菜单添加
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'uri'   => 'required|string',
        ],[
            'title.required' => '菜单名称不能为空',
            'uri.required'   => '菜单路由不能为空',
        ]);

        // id 存在更新。不存在添加
        if($request->id)
        {
            $result = $this->ruleService->update($request->id, $request->all());

            if($result)
            {
                return ajaxSuccess('修改成功！');
            }else{
                return ajaxError('修改失败！');
            }
        }else{
            $result = $this->ruleService->add($request->all());
            if($result)
            {
                return ajaxSuccess('添加菜单成功！');
            }else{
                return ajaxError('添加菜单失败！');
            }
        }
    }

    /**
     * 是否验证修改
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveCheck(Request $request)
    {
        $status = $request->status == 'true' ? '1' : '0';

        $result = $this->ruleService->updateCheck($request->id, $status);

        if($result)
        {
            return ajaxSuccess('修改成功！');
        }else{
            return ajaxError('修改失败！');
        }
    }

    public function destroy(Request $request)
    {
        $result = $this->ruleService->destroy($request->id);
        if($result)
        {
            return ajaxSuccess('删除成功！');
        }else{
            return ajaxError('删除失败！');
        }
    }

}
