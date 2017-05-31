<?php
/**
 * Created by PhpStorm.
 * User: Lynn
 * Date: 2017/5/31
 * Time: 9:56
 */

namespace wstmart\vv\controller;


use think\Controller;
use think\Db;

class User extends Controller {
    public $request;

    public function __construct() {
        $this -> request = request();
    }

    public function changePass($mobile, $code, $password) {
        $param = $this->request->param();
        if (!empty($param) && preg_match('/^\d{11}$/', trim($param['mobile']))) {
            $data = [
                'mobile' => $param['mobile'],
                'code' => $param['code'],
            ];
            $expire = time() - 30*60;
            Db::table('jingo_user_code') -> where('reg_time', '<', $expire) -> delete();
            $arr = Db::table('jingo_user_code') -> where($data) -> find();
            if ($arr) {
                $boolen = Db::table('jingo_users') -> where('userPhone', $param['mobile']) -> update(['loginPwd'=>md5($param['password'])]);
                if ($boolen) {
                    return urldecode(json_encode(['result'=>'success', 'value'=>urlencode('密码修改成功')]));
                }else {
                    return urldecode(json_encode(['result'=>'error', 'value'=>urlencode('网络又开小差了,请稍候重试')]));
                }
            }else {
                return urldecode(json_encode(['result'=>'error', 'value'=>urlencode('您的验证码不正确')]));
            }
        }else {
            return urldecode(json_encode(['result'=>'error', 'value'=>urlencode('请传参')]));
        }
    }
}