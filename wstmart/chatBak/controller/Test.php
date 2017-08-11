<?php
/**
 * Created by PhpStorm.
 * User: Me
 * Date: 2017/7/20
 * Time: 16:23
 */

namespace wstmart\chat\controller;


use think\Db;

class Test
{
    public function decrypt() {
        $private_key = file_get_contents(APP_PATH."chat/controller/rsa_key/rsa_private_key.pem");
        /*$private_key = '-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEAo1yoCj6SbIzo3jlxe/VJii7Q74VoC6hB3kyoETkkJBY8RXm3
u8KNXsUzwkots6uuQN/wd8GvJOLALQkkE2mFZshFkZgkbiY6vSVn9uP5BFlIxn1d
bgIrl370RyaxTwxf5J3UkKCKXPUR8k14gyK1UIkwG2BN2Qn0G00sXMIOH++ZclKU
r1Q+sIzwuefuUz5R/DTBDf4rvW9mH4aJ+qOpOd4UWZBjk4UIkFx43CyxLH17uo60
C0hlxk5597hwxT12Yxpo7XImoez8a4JB4dnDJT1sFZ7xOyFOfhvUH6M7gbryuLYl
j0F4rCnE1Mhy9kD/1aIUJUBBuShww1Dn7fs4iQIDAQABAoIBADoETx2MSV5J0O4T
Qo1+eIadx9A49dGmCPaVDN9JBt/WEcOuzaTeHGz65j3rMD3uC+24WmN9ouEbspqL
84b9Y8Yz/BkQCmggJZ5FKZ4SKr7zopfzr/nAxZCBIuoeALyNLYU41GQkz97hnmUK
RHF2IySeBDrEwgEBuvLzPn0W4gNO+FMF/EeDU77LHH+4U9cu5hfCbyJ10Y+hAMTa
lPbqpVfrAL2uNxYh7hbaCKi80iptnf0lNVPH+L3lpO50kfHCh/4GWMnxm5+gxu/x
TVK72zN1nYOXCUCjFj/Zfd4Ua4aC9bqvnoa3NfUseszMn8eriOKQqqmB6YOjOfAg
8z5oo10CgYEAzXvbsjCetQpyY77mWpGppbjm+OBDk90XAbkLvN/COhC5zt7cmYUR
aB9oEbXBxlxHk439h5vg+EnNlm9WErLi8T4Xs8QDsg/gZ8fP+DAnMPAvI6XnZGPP
fIdV9WRprYdAbZ0JKi22d2gB+sdMudh0KsQa5i4ZzfnqqCcWJoGfljcCgYEAy4Xa
nqMZEGrX0/grm+D2q5iBuFSRDNKwfTzKNyaynrPu7ixeUbagBBU46NzoEzh7l27/
mPvS8oqy5x4Htb2RkovQVap9DkGCtsAMjZniBW2zyLnBdkC9lsHnaKxwaRxcZvmW
EaDPy7rwY1HTcbVI7VS8hZeKjZl2f675EN1URz8CgYEAqLl8IyujFbr3RIn68gFL
UMWr+k2Dj8Vp38NHLRZ6kF0thP0gHHrUWS/4rxoUe8FIEJP1ysUnDBjl1BGlloMG
r9oxci2Qb0R0GiNK1FsNwzRlMJtIjfka5QDnhzClSZSWRas9XEHe4tsnTPkbOjrO
4FLYV4shKy18DgjSOloTZS8CgYATZZBuahDrdaNYOmC9nHUl6YUDclvYowPbC74h
/3gqk++Dl4rtssARY4xm9RbRLiLpTdTiWa3DG+DtEfmeIpELLHQQlg0RHME+dZM8
s5vM9KWbzchYYtcu1RAe88BK5D8SOVtKFs93FTvv206Rmbt3kwncmZLE6AlZJe3q
440ytQKBgQCxuuj30FgfyyxnfECUQPKfLH4i/eV7E4QoQHxoTlv9qjgJbe8ul23E
V8YulkNM069iKfltQBHU7/dRjWOtFEcoDT6jwPTjgCVHOkqFlVLwxEUQRMS/xI1o
f0Qg4Mo6sAZzJwTHZA7DZJE9PE+KrmZPOIWHn0B+sj8OppYY84MVRg==
-----END RSA PRIVATE KEY-----';

        $public_key = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAo1yoCj6SbIzo3jlxe/VJ
ii7Q74VoC6hB3kyoETkkJBY8RXm3u8KNXsUzwkots6uuQN/wd8GvJOLALQkkE2mF
ZshFkZgkbiY6vSVn9uP5BFlIxn1dbgIrl370RyaxTwxf5J3UkKCKXPUR8k14gyK1
UIkwG2BN2Qn0G00sXMIOH++ZclKUr1Q+sIzwuefuUz5R/DTBDf4rvW9mH4aJ+qOp
Od4UWZBjk4UIkFx43CyxLH17uo60C0hlxk5597hwxT12Yxpo7XImoez8a4JB4dnD
JT1sFZ7xOyFOfhvUH6M7gbryuLYlj0F4rCnE1Mhy9kD/1aIUJUBBuShww1Dn7fs4
iQIDAQAB
-----END PUBLIC KEY-----';*/

//echo $private_key;
        $param = request()->param();
        $pi_key =  openssl_pkey_get_private($private_key);//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
        //$pu_key = openssl_pkey_get_public($public_key);//这个函数可用来判断公钥是否是可用的
        print_r($pi_key);echo "\n";
        //print_r($pu_key);echo "\n";

        $encrypted = "";
        $decrypted = "";


        echo "public key encrypt:\n";
        openssl_public_encrypt('1234567',$encrypted,$pu_key);//公钥加密
        $encrypted = base64_encode($encrypted);
        echo $encrypted,"\n";

        echo "private key decrypt:\n";
        openssl_private_decrypt(base64_decode($encrypted),$decrypted,$pi_key);//私钥解密
        /*return $decrypted;*/
        echo $decrypted,"\n";
    }
}