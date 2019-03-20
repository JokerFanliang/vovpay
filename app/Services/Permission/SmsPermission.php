<?php
namespace App\Services\Permission;
class SmsPermission
{

    static private  $instance   =  array();
    static private  $_instance  =  null;

	/*
		@para $uid 必填 用户id
		@para $type 必填 模板内容
		@para $cell 选填 如果有内容必须是number类型
		@para $sendtime 选填 定时发送时间
		@return true;
	*/

	public  function  __construct()
    {
        $config=self::getConfig();
        self::getInstance($config);
    }

	public function check($uid,$num)
	{
		$member=M("member");
		$user=$member->where(['id'=>$uid])->find();
		if($user['smscode'] == $num)
		{
			$member->where(['id'=>$uid])->save(['smscode'=>null]);
			return true;
		}
		return false;
	}

    static function getConfig(){
        $config=[];
        $config['type']=C('SMSTYPE');
        $config['gateway']=C('SMSADDR');
        $config['name']=C('SMSNAME');
        $config['passwd']=C('SMSPASS');

        return $config;
    }


    /**
     * 取得数据库类实例
     * @static
     * @access public
     * @param mixed $config 连接配置
     * @return Object 返回数据库驱动类
     */
    static public function getInstance($config=array()) {

            // 解析连接参数 支持数组和字符串
//            $options    =   self::parseConfig($config);

            // 如果采用lite方式 仅支持原生SQL 包括query和execute方法
            $class  =  'Think\\Sms\\Driver\\'.ucwords(strtolower($config['type']));
            if(class_exists($class)){
                self::$_instance   =   new $class($config);
            }else{
                // 类没有定义
                E(L('_NO_SMS_DRIVER_').': ' . $class);
            }
    }


    // 调用驱动类的方法
//    static public function __callStatic($method, $params){
//        return call_user_func_array(array(self::$_instance, $method), $params);
//    }

    // 调用驱动类的方法

     public function __call($method, $params){
        return call_user_func_array(array(self::$_instance, $method), $params);
    }


	private function msg($result)
	{
		switch($result)
		{
			case '-1':
				return array('status'=>'error','code'=>$result,'msg'=>'账号未注册');
			break;
			case '-2':
				return array('status'=>'error','code'=>$result,'msg'=>'其他错误');
			break;
			case '-3':
				return array('status'=>'error','code'=>$result,'msg'=>'帐号或密码错误');
			break;
			case '-5':
				return array('status'=>'error','code'=>$result,'msg'=>'余额不足，请充值');
			break;
			case '-7':
				return array('status'=>'error','code'=>$result,'msg'=>'提交信息末尾未加签名，请添加中文的企业签名【 】');
			break;
			case '-6':
				return array('status'=>'error','code'=>$result,'msg'=>'定时发送时间不是有效的时间格式');
			break;
			case '-8':
				return array('status'=>'error','code'=>$result,'msg'=>'发送内容需在1到300字之间');
			break;
			case '-9':
				return array('status'=>'error','code'=>$result,'msg'=>'发送号码为空');
			break;
			case '-10':
				return array('status'=>'error','code'=>$result,'msg'=>'定时发送时间不是有效的时间格式');
			break;
			case '-11':
				return array('status'=>'error','code'=>$result,'msg'=>'屏蔽手机号码');
			break;
			case '-100':
				return array('status'=>'error','code'=>$result,'msg'=>'IP黑名单');
			break;
			case '-102':
				return array('status'=>'error','code'=>$result,'msg'=>'账号黑名单');
			break;
			case '-103':
				return array('status'=>'error','code'=>$result,'msg'=>'IP未导白');
			break;
            case '-200':
                return array('status'=>'error','code'=>$result,'msg'=>'您的手机号错误(为空或不正确)');
                break;
            case '-201':
                return array('status'=>'error','code'=>$result,'msg'=>'Cell只能是数字或者为空');
                break;
			default:
				return array('status'=>'ok','code'=>'1','msg'=>'发送成功');
				break;
		}
	}


}
