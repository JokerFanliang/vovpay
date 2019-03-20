<?php
/**
 * 自定义函数
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/10/31
 * Time: 10:59
 */

/**
 * @param string $msg
 * @param array $data
 * @param int $httpCode
 * @return \Illuminate\Http\JsonResponse
 */
function ajaxSuccess(string $msg = 'success', array $data = [], int $httpCode = 200)
{
    $return = [
        'status' => 1,
        'msg'    => $msg,
        'data'   => $data,
    ];
    return response()->json($return, $httpCode);
}

/**
 * @param string $errMsg
 * @param int $httpCode
 * @return \Illuminate\Http\JsonResponse
 */
function ajaxError(string $errMsg = 'error' ,int $httpCode = 200)
{
    $return = [
        'status' => 0,
        'msg'    => $errMsg
    ];
    return response()->json($return, $httpCode);
}

/**
 * @param $data
 * @param string $lefthtml
 * @param int $pid
 * @param int $lvl
 * @return array
 */
function tree($data , $lefthtml = '|— ' , $pid=0 , $lvl=1)
{
    $arr = [];
    foreach ($data as $k => $v) {
        if ($v['pid'] == $pid) {
            $v['ltitle'] = str_repeat($lefthtml, $lvl) . $v['title'];
            $arr[] = $v;
            unset($data[$k]);
            $arr = array_merge($arr, tree($data, $lefthtml, $v['id'], $lvl + 1));
        }
    }
    return $arr;
}

/**
 * @param array $data
 * @param array $checked
 * @param int $pid
 * @return array
 */
function ztreeData(array $data, array $checked, int $pid = 0)
{
    $arr = [];
    foreach ($data as $k => $v) {
        if ($v['pid'] == $pid) {
            if (in_array($v['id'], $checked)) {
                $v['checked'] = true;
            }
            $v['open'] = true;
            $arr[] = $v;
            unset($data[$k]);
            $arr = array_merge($arr, ztreeData($data, $checked, $v['id']));
        }
    }
    return $arr;
}

/**
 * 订单id
 * @return string
 */
function getOrderId()
{
    $uniQid    = uniqid('', true);
    $subUniQid = substr($uniQid, 7, 17);
    $strSplit  = str_split($subUniQid, 1);
    $arrayMap  = array_map('ord', $strSplit);
    $str = substr(implode(NULL, $arrayMap), 0, 6);
    return date('YmdHis') . $str;
}

/**
 * 生成随机位数的字符串
 * @param int $length
 * @return string
 */
function randomStr( int $length = 16)
{
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
}

/**
 * 生成商户号
 * @param int $id
 * @return string
 */
function getMerchant(int $id){

    $year    = date('y',time());
    $week    = date('W',time());
    $weekDay = date("w",time());
    $str     = $year.$week.$weekDay.$id;
    $randStr = mt_rand(str_pad(0,10-strlen($str),0),str_pad(9,10-strlen($str),9));
    return $str.$randStr;
}

/**
 * post表单提交
 * @param string $url
 * @param array $data
 */
function sendHttpPost(string $url, array $data)
{
    $str = '<form id="Form1" name="Form1" method="post" action="' . $url . '" >';
    foreach ($data as $key => $val) {
        $str .= '<input type="hidden" name="' . $key . '" value="' . $val . '">';
    }
    $str .= '</form>';
    $str .= '<script>';
    $str .= 'document.Form1.submit();';
    $str .= '</script>';
    exit();
}

/**
 * Curl 提交
 * @param $url
 * @param array $data
 * @param array $header
 * @param string $referer
 * @return mixed
 */
function sendCurl(string $url, array $data = [], array $header = [], string $referer = '')
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_URL, $url);

    if ($header) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
    if ($referer) {
        curl_setopt($ch, CURLOPT_REFERER, $referer);
    }
    if ($data) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    } else {
        curl_setopt($ch, CURLOPT_POST, false);
    }
    if (stripos($url, 'https://') !== false) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);   // 从证书中检查SSL加密算法是否存在
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $res = curl_exec($ch);
    if ( $res === false) {

        throw new App\Exceptions\CustomServiceException(sprintf('Curl error (code %s): %s', curl_errno($ch), curl_error($ch)));
    }
    curl_close($ch);
    return $res;
}

/**
 * 将数据库中查出的列表以指定的 id 作为数组的键名
 * @param $arr
 * @param $key_name
 * @return array
 */
function convert_arr_key($arr, $key_name)
{
    $result = array();
    foreach($arr as $key => $val){
        $result[$val->$key_name] = objectToArray($val);
    }
    return $result;
}

/**
 * 对象转数组
 * @param $object
 * @return array
 */
function objectToArray($object) {
    return json_decode(json_encode($object), true);
}

/**
 * 根据收款人id，获取订单拥有着
 * @param int $uid
 * @return string
 */
function getOrderAccountInfo(int $uid)
{
    $userService = app( \App\Services\UserService::class);
    $user = $userService->findId($uid);
    if(!$user) return '-';
    return $user->username;
}

/**
 * 毫秒时间戳
 * @return float
 */
function TimeMicroTime()
{
    list($msec, $sec) = explode(' ', microtime());
    return (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
}

/**
 * 顺序取号根据时间权重取号
 * @param $a
 * @param $b
 * @return int
 */
function sortWeight($a, $b)
{
    if ($a['weight'] == $b['weight']) {
        return 0;
    }
    return ($a['weight'] < $b['weight']) ? -1 : 1;
}

/**获取本站域名端口
 * @return string
 */
function getDomainPort()
{
    $httpType = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    return $httpType . $_SERVER['HTTP_HOST'];
}