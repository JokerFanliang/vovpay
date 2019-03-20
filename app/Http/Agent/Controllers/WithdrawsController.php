<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/28
 * Time: 16:24
 */

namespace App\Http\Agent\Controllers;


use App\Http\Requests\ManageWithdrawRequest;
use App\Services\BankCardService;
use App\Services\BanksService;
use App\Services\WithdrawsService;
use App\Services\ChannelService;
use App\Http\Requests\WithdrawRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class WithdrawsController extends Controller
{
    protected $withdrawsService;
    protected $bankCardService;
    protected $banksService;
    protected $channelService;

    /**
     * WithdrawsController constructor.
     * @param WithdrawsService $withdrawsService
     */
    public function __construct(WithdrawsService $withdrawsService, BankCardService $bankCardService, BanksService $banksService, ChannelService $channelService)
    {
        $this->withdrawsService = $withdrawsService;
        $this->bankCardService = $bankCardService;
        $this->banksService = $banksService;
        $this->channelService = $channelService;

    }

    /**
     * 查询分页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $title='我的结算';

        $uid = Auth::user()->id;
        $data = $request->input();
        $data['user_id'] = $uid;

        $search = $this->withdrawsService->searchPage($data,10);
        $list = $search['list'];
        $info = $search['info'];
        $query = $request->input();

        return view('Agent.Withdraws.withdraws', compact('title','list', 'info', 'query'));
    }

    /**商户结算管理列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public  function manage(Request $request){

        $title = '结算管理';
        $data = $request->input();
        $data['agent_id']=Auth::user()->id;


        if (env('ADD_ACCOUNT_TYPE') != 3) {
            throw new CustomServiceException('非法操作!');
        }

        $search = $this->withdrawsService->searchPage($data, 10);

        $list = $search['list'];
        $withdrawInfoSum = $search['info'];
        $chanel_list = $this->channelService->getAll();

        $query = $request->query();
        return view("Agent.Withdraws.manage", compact('title', 'list', 'query', 'withdrawInfoSum', 'chanel_list'));
    }

    /**商户结算订单管理
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public  function doManage(Request $request){

        if (env('ADD_ACCOUNT_TYPE') != 3) {
            throw new CustomServiceException('非法操作!');
        }

        if ($request->type == 1) {
            //普通通道
            $chanel_list = $this->channelService->getAll();
        } elseif ($request->type == 2) {
            //代付通道
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

        //结算处理
        $result = false;
        if ($data['type'] == 1) {
            //普通结算
            $result = $this->withdrawsService->commonWithdraw($data);
        } elseif ($data['type'] == 2) {
            //代付计结算
            $result = $this->withdrawsService->paidWithdraw($data);
        }

        if ($result) {
            return ajaxSuccess('结算操作成功');
        } else {
            return ajaxError('结算操作失败');
        }
    }


    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clearing(Request $request)
    {
        $title='账户结算';
        $uid = Auth::user()->id;
        $list = $this->bankCardService->getUserIdAll($uid);


        $data = $request->input();
        $data['user_id'] = $uid;

        $search = $this->withdrawsService->searchPage($data,10);
        $clearings = $search['list'];
        $info = $search['info'];


        $banks= $this->banksService->findAll();

        $WithdrawRule=$this->withdrawsService->getWithdrawRule();

        return view('Agent.Withdraws.clearing', compact('title','list', 'banks', 'clearings', 'WithdrawRule'));
    }

    /**
     * 申请结算
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(WithdrawRequest $request)
    {
            $result = $this->withdrawsService->add($request->input());

            if ($result) {
                return ajaxSuccess('结算申请中，请留意您的账单变化！');
            }
    }
}