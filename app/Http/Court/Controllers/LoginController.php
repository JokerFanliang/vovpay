<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/27
 * Time: 16:25
 */

namespace App\Http\Court\Controllers;

use App\Services\LoginLogoutService;
use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/Court';
    protected $loginLogoutService;
    protected $userService;

    /**
     * LoginController constructor.
     * @param LoginLogoutService $loginLogoutService
     */
    public function __construct(LoginLogoutService $loginLogoutService, UserService $userService)
    {
        $this->loginLogoutService = $loginLogoutService;
        $this->userService = $userService;
    }


    /**
     * 登录页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        $user = Auth::guard('court')->user();
        if ($user) return redirect('court');
        $google_auth=isset(Cache()->get('systems')['login_permission_type']->value) && Cache()->get('systems')['login_permission_type']->value == '1';
        return view('Court.Login.login',compact('google_auth'));
    }

    /**
     * 场外第三方登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {

        // 添加验证用户登录标识
        $request->merge(['group_type' => '3']);
        $request->merge(['status' => '1']);
        $check_data = $request->only('username','password','group_type','status','auth_code');
        $this->loginLogoutService->Login('court',$check_data);

        return ajaxSuccess('登录成功，欢迎来到后台管理系统。');
    }

    /**
     * 登出
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        $this->loginLogoutService->destroy('court');

        return redirect()->route('court.login');
    }


}