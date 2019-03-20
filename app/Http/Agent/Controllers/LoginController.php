<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/27
 * Time: 16:25
 */

namespace App\Http\Agent\Controllers;

use App\Services\LoginLogoutService;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/Agent';
    protected $loginLogoutService;

    /**
     * LoginController constructor.
     * @param LoginLogoutService $loginLogoutService
     */
    public function __construct(LoginLogoutService $loginLogoutService)
    {
        $this->loginLogoutService = $loginLogoutService;

    }


    /**
     * 登录页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        $user = Auth::guard('agent')->user();
        if ($user) return redirect('agent');

        $google_auth=isset(Cache()->get('systems')['login_permission_type']->value) && Cache()->get('systems')['login_permission_type']->value == '1';
        return view('Agent.Login.login',compact('google_auth'));
    }

    /**
     * 代理商登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {

        // 添加验证用户登录标识
        $request->merge(['group_type' => '2']);
        $request->merge(['status' => '1']);
        $check_data = $request->only('username','password','group_type','status','auth_code');

        $this->loginLogoutService->Login('agent',$check_data);

        return ajaxSuccess('登录成功，欢迎来到后台管理系统。');
    }

    /**
     * 登出
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        $this->loginLogoutService->destroy('agent');

        return redirect()->route('agent.login');
    }


}