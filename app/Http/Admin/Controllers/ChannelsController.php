<?php

namespace App\Http\Admin\Controllers;

use App\Services\ChannelService;
use Illuminate\Http\Request;

class ChannelsController extends Controller
{
    protected $channelService;

    public function __construct( ChannelService $channelService)
    {
        $this->channelService = $channelService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $title = '通道管理';
        $query = $request->query();

        $list = $this->channelService->getAllPage(10);
        return view('Admin.Channels.index',compact('title', 'list', 'query'));
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
            'channelName'     => 'required|max:30',
            'channelCode'     => 'required|max:20|unique:channels,channelCode,'.$id,
        ],[
            'channelName.required'      => '通道名称不能为空！',
            'channelName.max'           => '通道名称最大长度30个字符！',
            'channelCode.required'      => '通道编码不能为空！',
            'channelCode.max'           => '通道编码最大长度20个字符!',
            'channelCode.unique'        => '通道编码已存在！',
        ]);

        // id 存在更新。不存在添加
        if($id)
        {
            $result = $this->channelService->update($request->id, $request->all());

            if($result)
            {
                return ajaxSuccess('修改成功！');
            }else{
                return ajaxError('修改失败！');
            }
        }else{
            $result = $this->channelService->add($request->all());
            if($result)
            {
                return ajaxSuccess('添加成功！');
            }else{
                return ajaxError('添加失败！');
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
        $rule =$this->channelService->findId($id);
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

        $result = $this->channelService->updateStatus($request->id, $data);

        if($result)
        {
            return ajaxSuccess('修改成功！');
        }else{
            return ajaxError('修改失败！');
        }
    }

    /**
     * 结算方式变更
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function savePlanType(Request $request)
    {
        $result = $this->channelService->updateStatus($request->id, $data);

        if($result)
        {
            return ajaxSuccess('修改成功！');
        }else{
            return ajaxError('修改失败！');
        }
    }

    /**
     * 删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $result = $this->channelService->destroy($request->id);
        if($result)
        {
            return ajaxSuccess('删除成功！');
        }else{
            return ajaxError('删除失败！');
        }
    }

}
