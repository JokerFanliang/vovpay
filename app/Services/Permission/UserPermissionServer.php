<?php

namespace App\Services\Permission;


use App\Repositories\SystemsRepository;
use App\Exceptions\CustomServiceException;
use Illuminate\Support\Facades\Auth;
use App\Services\Permission\GoogleAuthenticator;


class UserPermissionServer
{
    static private $googleAuthenticator;

    public function __construct(GoogleAuthenticator $googleAuthenticator)
    {
        static::$googleAuthenticator=$googleAuthenticator;
    }


    /**操作权限验证
     * @param $code
     * @return bool
     */
    public function checkPermission($code)
    {

        $PermissionType = SystemsRepository::findKey('withdraw_permission_type');

        switch ($PermissionType) {
//            case 'SMS':
//                $result = SmsPermission::check($code);
//                break;
            case 'GOOGLE':
                $result = static::verifyCode($code);
                break;
            case 'PASSWORD':
                $result = static::checkPasswordPermission($code);
                break;
            default :
                throw new  CustomServiceException('不存在的验证方式:' . $PermissionType . ',请联系平台管理员!');
        }
        return $result;
    }

    /** 支付密码验证
     * @param $code
     * @return boolean
     */
    protected static function checkPasswordPermission($code)
    {
        $status = password_verify($code, Auth::user()->payPassword) ? true : false;
        return $status ? true : false;
    }

    /**委托调用google验证
     * @param $auth_code
     * @return mixed
     */
    static  private function verifyCode($auth_code){

        return static::$googleAuthenticator->verifyCode(Auth::user()->google_key, $auth_code);
    }


}
