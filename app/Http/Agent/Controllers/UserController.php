<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/27
 * Time: 11:45
 */

namespace App\Http\Agent\Controllers;


use App\Services\AgentService;
use App\Services\CheckUniqueService;
use App\Services\StatisticalService;
use App\Services\UserService;
use App\Services\UserRateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AgentRateRequest;

class UserController extends Controller
{

    protected $userService;
    protected $checkUniqueService;
    protected $statisticalService;
    protected $userRateService;

    /**
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(StatisticalService $statisticalService, UserService $userService,CheckUniqueService $checkUniqueService,UserRateService $userRateService)
    {
        $this->userService = $userService;
        $this->checkUniqueService=$checkUniqueService;
        $this->statisticalService=$statisticalService;
        $this->userRateService=$userRateService;
    }

    /**
     * 下属用户展示
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $title  = '商户管理';
        $query = $request->input();
        $pid   = Auth::user()->id;

        if(count($query))
        {
            $query['parentId']=$pid;
            $list = $this->userService->searchPage($query, 10);
        }else{
            $list = $this->userService->getParentIdPage($pid,10);
        }

        return view('Agent.User.user',compact( 'title','list', 'query'));

    }

    /**
     * 个人信息
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        $title       = '账户信息';
        $uid         = Auth::user()->id;
        $user        = $this->userService->findId($uid);
        $statistical = $this->statisticalService->findUserId($uid);

        return view('Agent.User.index', compact('title','user','statistical'));
    }


    /**
     * 添加
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $data=$request->input();
        $data['parentId']=Auth::user()->id;
        $result = $this->userService->add($data);

        if ($result) {
            return ajaxSuccess('商户添加成功!');
        } else {
            return ajaxError('商户添加失败!');
        }
        
    }

    /**
     * 下属商户状态修改
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

    /**用户通道设置
     * @param $uid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public  function channel(AgentRateRequest $request,$id){

        $title = '用户通道设置';
        $uid=$id;
        $list  = $this->userRateService->getFindUidRate($uid);
        return view('Agent.User.channel',compact('title','list', 'uid'));
    }

    /**
     * 获取用户单条费率，不存在初始化
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserRate(AgentRateRequest $request)
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
     * 用户费率状态修改
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveUserRateStatus(AgentRateRequest $request)
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
     * 用户费率更新添加
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userRateStore(AgentRateRequest $request)
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

    /**检查用户是否配置google验证
     * @param Request $request
     */
    public function hasGoogleKey(Request $request){

        $result=$this->userService->hasGoogleKey($request->username);

        if($result)
        {
            return ajaxSuccess('用户已配置google_key');
        }else{
            return ajaxError('用户未配置google_key！');
        }
    }

}