<?php
/**
 * Created by PhpStorm.
 * User: Me
 * Date: 2017/7/20
 * Time: 16:22
 */

namespace wstmart\chat\controller;


class Rsa {
    public $privateKey = '';
    public $publicKey = '';

    //初始化公钥和私钥
    public function __construct() {
        $resource = openssl_pkey_new();
        openssl_pkey_export($resource, $this->privateKey);
        $detail = openssl_pkey_get_details($resource);
        $this->publicKey = $detail['key'];
    }

    //公钥加密
    public function publicEncrypt($data, $publicKey) {
        openssl_public_encrypt($data, $encrypted, $publicKey);
        return $encrypted;
    }

    //公钥解密
    public function publicDecrypt($data, $publicKey) {
        openssl_public_decrypt($data, $decrypted, $publicKey);
        return $decrypted;
    }

    //私钥加密
    public function privateEncrypt($data, $privateKey) {
        openssl_private_encrypt($data, $encrypted, $privateKey);
        return $encrypted;
    }

    //私钥解密
    public function privateDecrypt($data, $privateKey) {
        openssl_private_decrypt($data, $decrypted, $privateKey);
        return $decrypted;
    }
}