<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App;
use  App\Exceptions\CustomServiceException;
use App\Services\Permission\GoogleAuthenticator;
use App\Services\Permission\UserPermissionServer;

class WithdrawRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        $UserPermissionServer=new UserPermissionServer(new GoogleAuthenticator);
         if(!$UserPermissionServer->checkPermission($this->auth_code)){
             throw new CustomServiceException('认证失败!');
         }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'withdrawAmount' => 'required|numeric',
            'bank_id' => 'required|integer',
            'auth_code' => 'required|alpha_num',
        ];

    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'withdrawAmount.required'   => '结算金额不能为空',
            'withdrawAmount.numeric'    => '结算金额必须为数字',
            'withdrawAmount.min'        => '结算金额不能小于100',
            'bank_id.required'          => '请选择结算银行卡',
            'bank_id.integer'           => '结算银行卡必须为数字',
            'auth_code.required'        => '验证信息不能为空',
            'auth_code.alpha_num'       => '验证信息格式不正确',
        ];
    }
}
