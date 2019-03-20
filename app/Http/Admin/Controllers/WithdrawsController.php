<?php

namespace App\Http\Admin\Controllers;

use App\Http\Requests\ManageWithdrawRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Services\WithdrawsService;
use App\Services\ChannelService;

class WithdrawsController extends Controller
{
    protected $userService;
    protected $withdrawsService;
    protected $channelService;


    public function __construct(UserService $userService, WithdrawsService $withdrawsService, ChannelService $channelService)
    {
        $this->userService = $userService;
        $this->withdrawsService = $withdrawsService;
        $this->channelService = $channelService;

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $title = '结算管理';

        $data = $request->input();

        $search = $this->withdrawsService->searchPage($data, 10);

        $list = $search['list'];
        $withdrawInfoSum = $search['info'];

        $chanel_list = $this->channelService->getAll();

        $query = $request->query();
        return view("Admin.Withdraws.index", compact('title', 'list', 'query', 'withdrawInfoSum', 'chanel_list'));
    }

    /**结算管理操作
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function manage(Request $request, $id)
    {

        if ($request->type == 1) {
            //普通结算
            $chanel_list = $this->channelService->getAll();
        } elseif ($request->type == 2) {
            //代付计结算
            $chanel_list = collect([]);
        }

        if ($chanel_list) {
            return ajaxSuccess('SUCCESS', $chanel_list->toArray());
        } else {
            return ajaxError('获取通道失败！');
        }


    }

    /**结算状态变更
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ManageWithdrawRequest $request)
    {

            $data = $request->input();

            $result=false;
            if ($data['type'] == 1) {
                //普通结算
                $result=$this->withdrawsService->commonWithdraw($data);
            } elseif ($data['type'] == 2) {
                //代付计结算
                $result=$this->withdrawsService->paidWithdraw($data);
            }

            if ($result) {
                return ajaxSuccess('结算操作成功');
            }else{
                return ajaxError('结算操作失败');
            }
    }


    /**
     * 用户费率更新添加
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userRateStore(Request $request)
    {
        $uid = $request->id;
        $payId = $request->payId;
        $channelId = $request->channelId;
        $rate = $request->rate;
        $status = $request->status;
        $result = $this->userRateService->userRateStore($uid, $channelId, $rate, $payId, $status);
        if ($result) {
            return ajaxSuccess('修改成功！');
        } else {
            return ajaxError('修改失败！');
        }
    }


    //查询是否存在申请
    public function checkNotice()
    {
        $count = $this->withdrawsService->checkNotice();
        return ajaxSuccess('获取成功',['count'=>$count]);
    }
}
