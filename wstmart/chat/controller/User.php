<?php
/**
 * Created by PhpStorm.
 * User: Lynn
 * Date: 2017/5/31
 * Time: 9:56
 */

namespace wstmart\chat\controller;


use think\Controller;
use think\Db;
use think\Validate;

class User extends Controller {
    public $request;
    private $api;

    public function __construct() {
        $this -> request = request();
        $this -> api = new Api();
    }

    public function test($value) {
        /*$param = $this->request->param();
        $validate = new Validate([
            ['value', 'max:20|/^[a-zA-Z0-9_]+$/', '请输入有效手机号'],
        ]);
        $data = [
            'value' => $param['value'],
        ];
        if (!$validate -> check($data)) {
            $this -> error($validate -> getError());
        }*/
    }

    //更换手机号
    public function changeMobile($mobile, $code, $userId, $targetMobile) {
        $param = $this -> request -> param();
        if (!empty($param)) {
            $arr = Db::table('jingo_user_code') -> where(['mobile'=>$param['mobile'], 'code'=>$param['code']]) -> find();       //获取短信验证码相关信息
            $expire = time() - 30*60;       //验证码失效时间点
            if ($arr) {     //如果验证码信息存在
                if ($expire < $arr['reg_time']) {       //并且验证码不超过30分钟
                    $boolen = Db::table('jingo_users') -> where(['userId'=>$param['userId'], 'userPhone'=>$param['mobile']]) -> find();
                    if ($boolen && Db::table('jingo_users') -> update(['userPhone'=>$param['targetMobile'], 'userId'=>$param['userId']])) {
                        return json(['result'=>'success', 'value'=>'成功更换手机号']);
                    }else {
                        return json(['result'=>'error', 'value'=>'更换手机号失败,请稍候重试']);
                    }
                }else {
                    Db::table('jingo_user_code') -> where('reg_time', '<', $expire) -> delete();        //删除过期验证码
                    return json(['result'=>'error', 'value'=>'您的验证码已过期']);
                }
            }else {
                return json(['result'=>'error', 'value'=>'您输入的验证码有误']);
            }
        }else {
            return json(['result'=>'error', 'value'=>'请传参']);
        }
    }

    //设置VVID
    public function setVVID($userId, $vvid) {
        $param = $this->request->param();
        if (!empty($param)) {
            $arr = Db::table('jingo_users') -> field(['loginName', 'isSeted']) -> where('userId', $param['userId']) -> find();
            if ($arr['isSeted'] == 0) {
                $validate = new Validate([
                    'vvid' => 'require|max:20|/^[a-zA-Z0-9_]+$/)',
                ]);       //验证用户名(必填,不大于20字符)
                $data = [
                    'vvid' => trim($param['vvid']),
                ];
                if ($validate -> check($data)) {
                    if (Db::table('jingo_users') -> where('userId', $param['userId']) -> update(['loginName'=>$param['vvid'], 'isSeted'=>1])) {
                        return json(['result'=>'success', 'value'=>'VVID设置成功']);
                    }else {
                        return json(['result'=>'error', 'value'=>'网络又开小差了,请稍后重试']);
                    }
                }
            }else {
                return json(['result'=>'error', 'value'=>'尊敬的客户您好!VVID只能设置一次']);
            }
        }else {
            return json(['result'=>'error', 'value'=>'请传参']);
        }
    }

    //修改密码
    public function changePass($mobile, $code, $userId, $password) {
        $param = $this -> request -> param();
        if (!empty($param)) {
            $arr = Db::table('jingo_user_code') -> where(['mobile'=>$param['mobile'], 'code'=>$param['code']]) -> find();       //获取短信验证码相关信息
            $expire = time() - 30*60;       //验证码失效时间点
            if ($arr) {     //如果验证码信息存在
                if ($expire < $arr['reg_time']) {       //并且验证码不超过30分钟
                    $arr2 = Db::table('jingo_users') -> field(['loginSecret']) -> where('userId', $param['userId']) -> find();     //查询用户校验码
                    $boolen = Db::table('jingo_users') -> where(['userPhone'=>$param['mobile'], 'userId'=>$param['userId']]) -> update(['loginPwd'=>md5($param['password'].$arr2['loginSecret']), 'userId'=>$param['userId']]);       //更新密码
                    if ($boolen) {
                        return urldecode(json_encode(['result'=>'success', 'value'=>urlencode('密码修改成功')]));
                    }else {
                        return urldecode(json_encode(['result'=>'error', 'value'=>urlencode('网络又开小差了,请稍候重试')]));
                    }
                }else {
                    Db::table('jingo_user_code') -> where('reg_time', '<', $expire) -> delete();        //删除过期验证码
                    return json(['result'=>'error', 'value'=>'您的验证码已过期']);
                }
            }else {
                return json(['result'=>'error', 'value'=>'您输入的验证码有误']);
            }
        }else {
            return json(['result'=>'error', 'value'=>'请传参']);
        }
    }

    //生成个人二维码
    public function setQRcode($userId) {
        include_once "phpqrcode.php";
        $param = $this->request->param();
        if (!$param) {
            exit();
        }
        $serialize = json_encode([              //二维码信息
            'userId'=>$param['userId'],
            'nickName'=>$param['nickName']
        ]);
        $rand = mt_rand(10000000, 99999999);
        $time = time();
        $name = md5($rand).md5($time);          //生成二维码名
        $date = date("Ymd",time());
        if (!file_exists(ROOT_PATH.'upload/qrcode/personal/'.$date)) {
            mkdir(ROOT_PATH.'upload/qrcode/personal/'.$date);
        }

        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 2;//生成图片大小
        $path = $date.'/'.$name.'.png';
        $map = [
            'userId' => $param['userId']
        ];
        QRcode::png($serialize, ROOT_PATH.'upload/qrcode/personal/'.$date.'/'.$name.'.png', $errorCorrectionLevel, $matrixPointSize, 2);

        $arr = Db::table('jingo_users') -> field('userPhoto') -> where('userId', $param['userId']) -> find();
        if ($arr) {
            $logo = ROOT_PATH.$arr['userPhoto'];//准备好的logo图片
            $QR = ROOT_PATH.'upload/qrcode/personal/'.$date.'/'.$name.'.png';//已经生成的原始二维码图

            if ($logo !== FALSE) {
                $QR = imagecreatefromstring(file_get_contents($QR));
                $logo = imagecreatefromstring(file_get_contents($logo));
                $QR_width = imagesx($QR);//二维码图片宽度
                $QR_height = imagesy($QR);//二维码图片高度
                $logo_width = imagesx($logo);//logo图片宽度
                $logo_height = imagesy($logo);//logo图片高度
                $logo_qr_width = $QR_width / 5;
                $scale = $logo_width / $logo_qr_width;
                $logo_qr_height = $logo_height / $scale;
                $from_width = ($QR_width - $logo_qr_width) / 2;

                //重新组合图片并调整大小
                imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
            }

            //输出图片
            imagepng($QR, ROOT_PATH.'upload/qrcode/personal/'.$date.'/'.$name.'.png');
        }
        if (file_exists(ROOT_PATH.'upload/qrcode/personal/'.$date.'/'.$name.'.png')) {
            if (Db::table('jingo_users') -> where($map) -> update(['qrcode'=>$path])) {
                return json(array('result'=>'success', 'value'=>'二维码图片上传成功'));
            }else {
                unlink(ROOT_PATH.'upload/qrcode/personal/'.$date.'/'.$name.'.png');     //如果更新个人二维码路径失败则删除二维码(linux下注意权限问题)
                return json(array('result'=>'error', 'value'=>'二维码图片路径保存失败,请稍候重试'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'二维码图片失效,请重新生成'));
        }
    }

}