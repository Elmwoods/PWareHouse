<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/9 0009
 * Time: 下午 04:55
 */
namespace wstmart\purse\controller;

use think\Controller;
//还贷款控制器
class Index extends Base
{

    public function login(){
        $USER = session('WST_USER');
//         var_dump($USER);
        //如果已经登录了则直接跳去钱袋子
        if(!empty($USER) && !empty($USER['userId']) && $USER['userId']!=''){
            $this->redirect("purse/consume/index");
        }
        $serialize = $this->setqrcode();
        $date = date("Ymd",time());
        $this->assign('data',$date);
        $this -> assign('serialize', $serialize);
//        dump($serialize);
        return $this->fetch('purse1');
    }

    public function agreement(){
        return $this->fetch('agreement');
    }

    public function checkToken($token_id, $token) {
        //$sql = "SELECT login_time, member_name FROM jingo_user_token WHERE token_id = '$token_id' AND token = '$token' LIMIT 1";
        //$result = pdo() -> query($sql);
        //$arr = $result -> fetch(2);
        $arr = \think\Db::table('jingo_user_token') -> field(['login_time', 'member_name', 'member_id']) -> where(['token_id'=>$token_id, 'token'=>$token]) -> find();
        if ($arr) {
            if ((time() - intval($arr['login_time'])) > 7*24*3600) {
                return 'invalid';
            }else {
                $request = array();
                $request['value'] = 'success';
                $request['name'] = $arr['member_name'];
                $request['id'] = $arr['member_id'];
                return $request;
            }
        }else {
            return 'error';
        }
    }

    public function setqrcode() {
        include_once 'phpqrcode.php';

        //生成序列号
        $rand = mt_rand(10000000, 99999999);
        $time = time();
        $serialize = 'loginPC&'.md5($rand).md5($time);
        $date = date("Ymd",time());
        if (!file_exists(ROOT_PATH.'public/vvoff/serialize/'.$date)) {
            mkdir(ROOT_PATH.'public/vvoff/serialize/'.$date);
        }

        //生成二维码
        $expire = $time - 60;      //1分钟之前的时间点
        \think\Db::table('jingo_user_token') -> where('set_time', '<', $expire) -> delete();
        \think\Db::table('jingo_user_token') -> insert(['serialize'=>$serialize, 'set_time'=>$time]);
        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 6;//生成图片大小
        //生成二维码图片
        QRcode::png($serialize, ROOT_PATH.'public/vvoff/serialize/'.$date.'/'.$serialize.'.png', $errorCorrectionLevel, $matrixPointSize, 2);
        $logo = ROOT_PATH.'public/vvoff/serialize/logo.png';//准备好的logo图片
        $QR = ROOT_PATH.'public/vvoff/serialize/'.$date.'/'.$serialize.'.png';//已经生成的原始二维码图

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
        imagepng($QR, ROOT_PATH.'public/vvoff/serialize/'.$date.'/'.$serialize.'.png');
        return $serialize;
    }

    //用于手机扫码响应
    public function valid() {
        if (isset($_POST['token_id'])) {
            $_POST['token'] = str_replace('\/', '/', $_POST['token']);          //将json格式转换的\/替换为/
            $request = $this->checkToken($_POST['token_id'], $_POST['token']);         //获取检查token之后的返回值
            if (is_array($request) && $request['value'] == 'success') {         //如果返回值是数组并且有success值则往下执行
                $boolen = \think\Db::table('jingo_user_token') -> where('serialize', $_POST['serialize']) -> update(['token'=>$_POST['token'], 'member_name'=>$request['name'], 'member_id'=>$request['id']]);
                //$expire = time() - 60;
                //$sql1 = "DELETE FROM jingo_user_token WHERE set_time < '$expire'";
                //pdo() -> exec($sql1);
                //Db::table('jingo_user_token') -> where('set_time', '<', $expire) -> delete();
                if ($boolen) {
                    return urldecode(json_encode(array('result' => 'success','value' => urlencode('验证成功,已写入用户名'.$_POST['serialize']))));
                }else {
                    return urldecode(json_encode(array('result' => 'error','value' => urlencode('数据库更新失败'))));
                }
            }else {
                return urldecode(json_encode(array('result' => 'error','value' => urlencode('检查登录秘钥出错'))));
            }
        }
        else {
            return urldecode(json_encode(array('result' => 'error','value' => urlencode('请确认是否发送登录秘钥'))));
        }
    }

    public function check() {
//        echo json_encode($_POST);
        if (isset($_POST['check'])) {
            //echo ($_POST['check']);
            //$sql = "SELECT member_name FROM jingo_user_token WHERE serialize = '{$_POST['check']}'";      //查询是否存在登录页面的二维码序列号
            //$result = pdo() -> query($sql);
            //$arr = $result -> fetch(2);
            $arr = \think\Db::table('jingo_user_token') -> field(['member_name', 'member_id']) -> where('serialize', $_POST['check']) -> find();
//            echo json_encode($arr);
            if ($arr['member_name'] != null) {
                $expire = time() - 120;      //2分钟之前的时间点
                \think\Db::table('jingo_user_token') -> where('set_time', '<', $expire) -> delete();
                $user = \think\Db::name('users')->where('userId',$arr['member_id'])->find();
                //setcookie('name', $arr['member_name'], time() + 24*3600);           //设置cookie,用于免登陆
                session('WST_USER',$user);
                //将登录时间和ip存入数据表
                $ip = request()->ip();
                $update = [];
                $update = ["lastTime"=>date('Y-m-d H:i:s'),"lastIP"=>$ip];
//                dump($update);die;
                \think\Db::name('users')->where(["userId"=>$user['userId']])->update($update);
                echo 'true';
            }else {
                echo 'false';
            }
        }else {
            echo 'false';
        }
    }



}