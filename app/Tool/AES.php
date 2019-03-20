<?php

namespace App\Tool;

class AES
{
    /**
     * 加密
     * @param string $data
     * @param string $key
     * @return string
     */
    public function encrypt( string $data, string $key) {
        return base64_encode(openssl_encrypt($data, 'aes-128-ecb', $key, OPENSSL_PKCS1_PADDING));//OPENSSL_PKCS1_PADDING 不知道为什么可以与PKCS5通用,未深究
    }

    /**
     * 解密
     * @param string $data
     * @param string $key
     * @return string
     */
    public function decrypt( string $data, string $key) {
        return openssl_decrypt(base64_decode($data), 'aes-128-ecb', $key, OPENSSL_PKCS1_PADDING);//OPENSSL_PKCS1_PADDING 不知道为什么可以与PKCS5通用,未深究
    }

}