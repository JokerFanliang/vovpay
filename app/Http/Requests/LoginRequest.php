<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
        return [
            //
            'username' => 'required|string',
            'password' => 'required',
            'captcha'  => 'required|captcha',
            'auth_code'=> 'nullable|alpha_num'
        ];
    }
    public function messages(){
        return [
            'username.string'       =>  '用户名格式错误',
            'captcha.required'      =>  '验证码不能为空',
            'captcha.captcha'       =>  '请输入正确的验证码',
            'auth_code.alpha_num'   =>  'google认证码格式错误'
        ];
    }
}
