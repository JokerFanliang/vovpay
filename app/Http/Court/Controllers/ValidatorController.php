<?php

namespace App\Http\Court\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\Permission\GoogleAuthenticator;


class ValidatorController extends Controller
{

    protected $googleAuthenticator;
    protected $userService;

    /**
     * ValidatorController constructor.
     * @param GoogleAuthenticator $googleAuthenticator
     * @param UserService $userService
     */
    public function __construct(GoogleAuthenticator $googleAuthenticator, UserService $userService)
    {
        $this->googleAuthenticator = $googleAuthenticator;
        $this->userService         = $userService;
    }

    public function index()
    {
        $secret     = $this->googleAuthenticator->createSecret();
        $name       = Auth::user()->username.'@'.$_SERVER['HTTP_HOST'];
        $qrCodeUrl  = $this->googleAuthenticator->getQRCodeGoogleUrl($name, $secret);
        $module     = 'Court';

        return view('Common.validator',compact('qrCodeUrl','secret','module'));
    }

    public function store(Request $request)
    {
        if (!password_verify($request->input('userPwd'), Auth::user()->password)) {
            return ajaxError('密码错误');
        }

        $checkResult = $this->googleAuthenticator->verifyCode($request->input('secret'),$request->input('appCode'),2);

        if (!$checkResult) {
            return ajaxError('验证码错误');
        }

        $result = $this->userService->updateGooleAuth(Auth::user()->id, ['google_key'=>$request->input('secret')] );

        if($result)
        {
            return ajaxSuccess('设置成功');
        }else{
            return ajaxSuccess('设置失败，请重试');
        }

    }
}
