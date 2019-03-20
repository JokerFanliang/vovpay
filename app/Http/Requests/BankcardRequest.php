<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class BankcardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $bankCardNo=$this->id?:'';
        return [
            'bank_id' => 'required|numeric',
            'accountName' => 'required',
            'id' => 'nullable|numeric',
            'branchName'=>'required',
            'bankCardNo'=>'required|alpha_num|unique:bank_cards,bankCardNo,'.$bankCardNo,
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
            'bank_id.required'          => '必须选择所属银行',
            'bank_id.numeric'           => '银行信息错误',
            'accountName.required'      => '银行开户名不能为空',
            'id.numeric'                => '错误的银行卡',
            'branchName.required'       => '支行信息不能为空',
            'bankCardNo.required'       => '银行卡号不能为空',
            'bankCardNo.alpha_num'      => '银行卡号格式错误',
            'bankCardNo.unique'         => '已存在的银行卡号',
        ];
    }
}
