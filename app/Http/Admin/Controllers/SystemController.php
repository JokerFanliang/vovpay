<?php

namespace App\Http\Admin\Controllers;

use Illuminate\Http\Request;
use App\Services\SystemsService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SystemController extends Controller
{
    protected $systemsService;

    public function __construct(SystemsService $systemsService)
    {
        $this->systemsService = $systemsService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $title = '系统配置';
        $list = $this->systemsService->getAll();

        return view('Admin.System.index', compact('title', 'list'));
    }

    public function store(Request $request)
    {
        $id = $request->id ? $request->id : '';

        $this->validate($request, [
            'name' => 'required_without:id|regex:/^[A-Za-z]+$/ |unique:systems,name,' . $id,
            'value' => 'required',
            'remark' => 'required',
        ], [
            'name.required_without' => '配置键名不能为空',
            'name.regex' => '配置键名格式不正确',
            'name.unique' => '配置键名已存在',
            'value.required' => '配置键值不能为空',
            'remark.required' => '配置说明不能为空',
        ]);

        // id 存在更新。不存在添加
        if ($id) {
            $result = $this->systemsService->update($request->id, $request->all());

            if ($result) {
                $this->systemCacheUpdate();
                return ajaxSuccess('修改成功！');
            } else {
                return ajaxError('修改失败！');
            }
        } else {
            $result = $this->systemsService->add($request->all());
            if ($result) {
                $this->systemCacheUpdate();
                return ajaxSuccess('添加成功！');
            } else {
                return ajaxError('添加失败！');
            }
        }
    }

    protected function systemCacheUpdate()
    {
        Cache::forget('systems');
        Cache::rememberForever('systems', function () {
            $system = DB::table('systems')->select('name', 'value')->get();
            $system_array = [];
            foreach ($system as $k => $v) {
                $system_array[$v->name] = $v;
            }
            return $system_array;
        });
    }

    /**
     * 编辑
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $system = $this->systemsService->findId($id);
        return ajaxSuccess('获取成功', $system->toArray());
    }

}