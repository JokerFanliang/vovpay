<?php

namespace App\Http\Admin\Controllers;

use App\Services\ChannelPaymentsService;
use App\Services\ChannelService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\UploadService;

class ChannelPaymentsController extends Controller
{
    protected $channelPaymentsService;
    protected $channelService;
    protected $uploadService;

    public function __construct( ChannelPaymentsService $channelPaymentsService, ChannelService $channelService, UploadService $uploadService)
    {
        $this->channelPaymentsService = $channelPaymentsService;
        $this->channelService = $channelService;
        $this->uploadService  = $uploadService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $title = '支付方式管理';
        $query = $request->query();

        $channel_list = $this->channelService->getAll();
        $list = $this->channelPaymentsService->getAllPage(10);

        return view('Admin.ChannelPayments.index',compact('title', 'list', 'query', 'channel_list'));
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
            'paymentName'     => 'required|max:30',
            'paymentCode'     => 'required|max:30|unique:channel_payments,paymentCode,'.$id,
            'channel_id'      => [ 'required', Rule::exists('channels','id')],
        ],[
            'paymentName.required'      => '支付名称不能为空！',
            'paymentName.max'           => '支付名称最大长度30个字符！',
            'paymentCode.required'      => '支付编码不能为空！',
            'paymentCode.max'           => '支付编码最大长度10个字符!',
            'paymentCode.unique'        => '支付编码已存在！',
            'channel_id.required'       => '所属通道不能为空',
            'channel_id.exists'         => '所属通道不存在',
        ]);

        // id 存在更新。不存在添加
        if($id)
        {
            $result = $this->channelPaymentsService->update($request->id, $request->all());

            if($result)
            {
                return ajaxSuccess('修改成功！');
            }else{
                return ajaxError('修改失败！');
            }
        }else{
            $result = $this->channelPaymentsService->add($request->all());
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
        $rule =$this->channelPaymentsService->findId($id);
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

        $result = $this->channelPaymentsService->updateStatus($request->id, $data);

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
        $result = $this->channelPaymentsService->destroy($request->id);
        if($result)
        {
            return ajaxSuccess('删除成功！');
        }else{
            return ajaxError('删除失败！');
        }
    }

    public function upload(Request $request)
    {
        $path = $this->uploadService->upload($request->ico,'payments','payment');
        return ajaxSuccess('上传成功', ['path'=>$path]);
    }

}
