<?php

namespace App\Http\User\Controllers;

use App\Services\LoginLogoutService;
use App\Services\UserService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    protected $loginLogoutService;
    protected $userService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct(UserService $userService, LoginLogoutService $loginLogoutService)
    {
        $this->loginLogoutService=$loginLogoutService;
        $this->userService=$userService;
    }


    public function show()
    {
        $user = Auth::guard('user')->user();
        if ($user) return redirect('user');
        $google_auth=isset(Cache()->get('systems')['login_permission_type']->value) && Cache()->get('systems')['login_permission_type']->value == '1';
        return view('User.Login.login',compact('google_auth'));
    }

    public function login(LoginRequest $request)
    {
        // 添加验证用户登录标识
        $request->merge(['group_type' => '1']);
        $request->merge(['status' => '1']);

        $check_data = $request->only('username','password','group_type','status','auth_code');
        $this->loginLogoutService->Login('user',$check_data);

        return ajaxSuccess('登录成功，欢迎来到后台管理系统。');

    }

    /*
     * 登出
     */
    public function destroy()
    {
        $this->loginLogoutService->destroy('user');

        return redirect()->route('user.login');
    }

    //注册页面
    public function registerShow()
    {
        return view('User.Login.register');
    }

    public function register(Request $request)
    {
        $data=$request->input();
        unset($data['rpassword']);
        $result=$this->userService->add($data);
        if($result)
        {
            return ajaxSuccess('注册成功，请登录。');
        }else{
            return ajaxSuccess('系统繁忙，请稍后重试！');
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
