<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/17
 * Time: 13:51
 */

namespace App\Http\User\Controllers;


use App\Services\BankCardService;
use App\Services\BanksService;
use App\Http\Requests\BankcardRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\CheckUniqueService;

class BankCardController extends Controller
{
    protected $bankCardService;
    protected $banksService;
    protected $checkUniqueService;

    public function __construct(BankCardService $bankCardService,BanksService $banksService, CheckUniqueService $checkUniqueService)
    {
        $this->bankCardService = $bankCardService;
        $this->banksService = $banksService;
        $this->checkUniqueService = $checkUniqueService;
    }

    /*
     * 银行卡管理
     */
    public function bankCard()
    {
        $id = Auth::user()->id;
        $lists = $this->bankCardService->getUserIdAll($id);
        $banks= $this->banksService->findAll();
        return view('User.BankCard.bankCard', compact('lists','banks'));
    }


    /**
     * 变更状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveStatus(Request $request)
    {
        $data['status'] = $request->status == 'true' ? '1' : '0';
        $data['user_id'] = $request->user_id;
        $result = $this->bankCardService->updateStatus($request->id, $data);
        if ($result) {
            return ajaxSuccess('修改成功！');
        } else {
            return ajaxError('修改失败！');
        }
    }

    /*
     * 银行卡添加
     */
    public function store(BankcardRequest $request)
    {
        $id = $request->id ? $request->id : '';

        if ($id) {
            $result = $this->bankCardService->update($request->id, $request->input());
            if ($result) {
                return ajaxSuccess('编辑成功！');
            } else {
                return ajaxError('编辑失败！');
            }
        } else {

            $result = $this->bankCardService->add($request->input());
            if ($result) {
                return ajaxSuccess('添加银行卡成功！');
            } else {
                return ajaxError('添加银行卡失败！');
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
        $result = $this->bankCardService->findId($id);
        if (!empty($result) && $result){
            return ajaxSuccess('获取成功', $result->toArray());
        }else{
            return ajaxError('获取失败');
        }

    }

    /**
     * 删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $result = $this->bankCardService->destroy($request->id);
        if ($result) {
            return ajaxSuccess('删除成功！');
        } else {
            return ajaxError('删除失败！');
        }
    }

    /**
     * 检测唯一性
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkUnique(Request $request)
    {
        $result = $this->checkUniqueService->check('bank_cards', $request->type, $request->value, $request->id, $request->name);
        if ($result) {
            return response()->json(array("valid" => "true"));
        } else {
            return response()->json(array("valid" => "false"));
        }
    }


}