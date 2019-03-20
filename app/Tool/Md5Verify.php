<?php

namespace App\Tool;

class Md5Verify
{
    /**
     * 数据验签
     * @param array $data
     * @param string $key
     * @return string
     */
    public function getSign(array $data, string $key)
    {
        $para_filter = $this->paraFilter($data);
        $para_sort   = $this->argSort($para_filter);
        $prestr      = $this->createLinkString($para_sort);
        return $this->md5Encrypt($prestr, $key);
    }

    /**
     * 除去数组中的签名参数
     * @param array $data
     * @return array
     */
    public function paraFilter(array $data)
    {
        $para_filter = array();
        foreach ($data as $key=>$val)
        {
            if($key == 'sign' || $val == '' || $key == 'json')continue;
            else $para_filter[$key] = $data[$key];
        }
        return $para_filter;
    }

    /**
     * 对待签名参数数组排序
     * @param array $para
     * @return array
     */
    public function argSort(array $para)
    {
        ksort($para);
        reset($para);
        return $para;

    }

    /**
     *把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para
     * @return bool|string
     */
    public function createLinkString($para) {
        $arg  = "";
        foreach ($para as $key=>$val)
        {
            $arg.=$key."=".$val."&";
        }

        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){
            $arg = stripslashes($arg);
        }

        return $arg;
    }

    /**
     * MD5加密验证
     * @param string $prestr
     * @param string $key
     * @return string
     */
    public function md5Encrypt($prestr, $key) {
        $prestr = $prestr . 'key='.$key;
        return md5($prestr);
    }
}