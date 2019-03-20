<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/12/21
 * Time: 15:32
 */

namespace App\Http\Admin\Controllers;


use App\Services\AccountPhoneService;
use App\Services\AccountBankCardsService;
use Illuminate\Http\Request;


class AccountListController extends Controller
{
    protected $accountPhoneService;
    protected $accountBankCardsService;

    public function __construct(AccountPhoneService $accountPhoneService, AccountBankCardsService $accountBankCardsService)
    {
        $this->accountPhoneService     = $accountPhoneService;
        $this->accountBankCardsService = $accountBankCardsService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = $request->query();
        $title = '所有账号';

        if(count($query) && (isset($query['accountType']) &&  $query['accountType'] == 'alipay_bank'))
        {
            $account_list = $this->accountBankCardsService->getAllPage($query,20);
        }else{
            $account_list = $this->accountPhoneService->searchPhoneStastic($query,20);
        }
        $account_list->appends($request->query());
        return view("Admin.Account.index", compact('account_list','title','query'));
    }

    /**
     * 编辑状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveAllStatus(Request $request)
    {
        $data['status'] = $request->status == 'true' ? '1' : '0';
        $accountType    = $request->accountType;

        if($accountType == '银行卡'){
            $result = $this->accountBankCardsService->update($request->id, auth()->user()->id, $data);
        }else{
            $result = $this->accountPhoneService->update($request->id, auth()->user()->id, $data);
        }

        if ($result) {
            return ajaxSuccess('修改成功！');
        } else {
            return ajaxError('修改失败！');
        }
    }


}