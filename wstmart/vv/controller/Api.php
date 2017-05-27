<?php
/**
 * Created by PhpStorm.
 * User: Lynn
 * Date: 2017/4/26
 * Time: 9:21
 */

namespace wstmart\vv\controller;


use think\Controller;
use think\Db;
use wstmart\admin\controller\privileges;

class Api extends Controller {
    public $request;

    public function __construct() {
        $this -> request = request();
    }
    
    //app初始化页面接口(用于验证是否免登陆)
    public function index() {
        $param = $this->request->param();
        if (!empty($param)) {
            $param['token'] = str_replace('\/', '/', $param['token']);
            switch ($this->checkToken($param['token_id'], $param['token'])) {          //验证token是否正确
                case 'success' :
                    return urldecode(json_encode(array('result' => 'success', 'value' => urlencode('验证通过,允许进入主界面'))));
                    break;
                case 'invalid' :
                    return urldecode(json_encode(array('result' => 'invalid', 'value' => urlencode('免登陆时间已过,请重新登录'))));
                    break;
                case 'error' :
                    return urldecode(json_encode(array('result' => 'error', 'value' => urlencode('免登陆验证失败,请重新登录'))));
                    break;
            }
        }
        return $this -> fetch('default/login');
    }

    //发送验证码
    public function send($mobile) {
        $param = $this->request->param();
        require_once 'YunpianAutoload.php';
        //session_start();
        // 发送单条短信
        //$smsOperator = new SmsOperator();
        $smsOperator = new \SmsOperator();

        if (isset($param['mobile']) && preg_match('/^\d{11}$/', trim($param['mobile']))) {      //判断post提交的手机号是否是11位数字,如果是则往下执行
            $mobile = trim($param['mobile']);       //post过来的手机号
            $code = mt_rand(100000, 999999);        //6位随机验证码
            $reg_time = time();                     //获取验证码的时间
            $data['mobile'] = $mobile;              //短信相关信息(手机号)
            $data['text'] = '【金购VV】亲爱的用户，您好！您的验证码是：'.$code.'，谢谢您的支持。(该验证码30分钟内有效)';       //短信相关信息(短信内容)

            /*$model = db('users');
            $arr = $model -> where('userPhone', $mobile) -> find();
            if ($arr) {         //如果为真,则表示该手机号已存在,返回错误
                $data = array(
                    'result' => 'error',
                    'value' => '该手机号已注册',
                );
                return json($data);        //返回json格式数据(error)
            }else {             //如果为假,继续发送短信*/
                $result = $smsOperator -> single_send($data) -> responseData;     //发送短信,并获取返回信息
                $returnCode = $result['code'];          //获取返回码
                switch ($returnCode) {
                    case 2 :
                        $data = array(
                            'result' => 'error',
                            'value' => urlencode('您确定有这样的手机号吗?'),
                        );
                        return urldecode(json_encode($data));
                        break;
                    case 22 :
                        $data = array(
                            'result' => 'error',
                            'value' => urlencode('同一手机号1小时内不得发送超过3次'),
                        );
                        return urldecode(json_encode($data));
                        break;
                    case 33 :
                        $data = array(
                            'result' => 'error',
                            'value' => urlencode('30秒内不得重复发送'),
                        );
                        return urldecode(json_encode($data));
                        break;
                    case 0 :
                        $data = array(
                            'result' => 'success',
                        );
                        $valid_code = db('user_code');
                        $values = array(
                            'mobile' => $mobile,
                            'code' => $code,
                            'reg_time' => $reg_time
                        );
                        $valid_code -> insert($values);
                        return urldecode(json_encode($data));        //返回json格式数据(success)
                        break;
                    default :
                        $data = array(
                            'result' => 'error',
                            'value' => urlencode('未知错误'),
                        );
                        return urldecode(json_encode($data));
                }
            //}
        }else {
            $data = array(
                'result' => 'error',
                'value' => urlencode('请输入11位有效手机号'),
            );
            return urldecode(json_encode($data));        //返回json格式数据(error)
        }
    }

    //注册
    public function reg($mobile, $code) {
        $param = $this->request->param();
        if (isset($param['mobile'])) {      //判断是否post提交
            $userCode = db('user_code');
            $arr1 = $userCode -> where('mobile', $param['mobile']) -> order('reg_time', 'DESC') -> find();      //查询该手机是否有验证码
            if (!$arr1) {
                $data = array(
                    'result' => 'error',
                    'value' => urlencode('未查询到验证码相关信息'),
                );
                return urldecode(json_encode($data));
                exit();
            }
            $expire = time() - $arr1['reg_time'];
            if ($arr1['mobile'] == $param['mobile'] && $arr1['code'] == intval($param['code']) && intval($expire) < 30*60) {       //判断提交的手机号和验证码是否与服务器保存的一致并且验证码存在不超过30分钟,如果是则往下执行
                $arr2 = Db::table('jingo_users') ->field(['userId', 'loginName', 'userPhoto']) -> where('userPhone', $param['mobile']) -> find();        //查询手机号相关用户
                if ($arr2) {        //如果已注册,则返回token和token_id
                    Db::table('jingo_user_token') -> where(['member_id'=>$arr2['userId'], 'member_name'=>$arr2['loginName']]) -> delete();      //删除旧有token
                    $token = getToken($arr2['userId'], $arr2['loginName'], $arr2['userPhoto']);
                    $values = array(
                        'token' => $token,
                        'member_id' => $arr2['userId'],
                        'member_name' => $arr2['loginName'],
                        'login_time' => time(),
                    );
                    $token_id = Db::table('jingo_user_token') -> insertGetId($values);
                    $data = array(
                        'result' => 'success',
                        'value' => array('token_id'=>$token_id, 'token'=>$token),
                    );
                    return urldecode(json_encode($data));
                    /*$tokenData = Db::table('jingo_user_token') -> field(['token_id', 'token']) -> where('member_id', $arr2['userId']) -> where('member_name', $arr2['loginName']) -> order('login_time', 'DESC') -> find();
                    if ($tokenData) {       //如果存在该用户的token,则返回相关信息
                        $data = array(
                            'result' => 'success',
                            'value' => $tokenData,
                        );
                        return json($data);
                    }else {         //如果不存在该用户的token,则生成token并写入数据库
                        $token = getToken($arr2['userId'], $arr2['loginName'], $arr2['userPhoto']);
                        $values = array(
                            'token' => $token,
                            'member_id' => $arr2['userId'],
                            'member_name' => $arr2['loginName'],
                            'login_time' => time(),
                        );
                        $token_id = Db::table('jingo_user_token') -> insertGetId($values);
                        $data = array(
                            'result' => 'success',
                            'value' => array('token_id'=>$token_id, 'token'=>$token),
                        );
                        return json($data);
                    }*/
                }else {             //如果未注册,则继续注册
                    $password = mt_rand(10000000,99999999);     //生成8位随机数字密码
                    $password_md5 = md5($password);
                    $loginSecret = mt_rand(1000, 9999);
                    $reg_time = date('Y-m-d H:i:s', time());

                    //生成随机用户名
                    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                    $length = 8;
                    $mobile_name = '';
                    for ($i = 0; $i < $length; $i++) {
                        $mobile_name .= $chars[mt_rand(0, strlen($chars) - 1)];     //随机生成的新用户名(昵称)
                    }
                    $loginName = $param['mobile'].'_a';     //用户登录名
                    $users = db('users');
                    $values = array(
                        'userPhone' => $param['mobile'],
                        'loginPwd' => $password_md5,
                        'createTime' => $reg_time,
                        'userName' => $mobile_name,
                        'loginName' => $loginName,
                        'loginSecret' => $loginSecret
                    );
                    $result = $users -> insert($values);
                    if ($result) {      //如果插入成功,则往下执行
                        $data = array(
                            'result' => 'success',
                            'value' => array(
                                'mobile' => $param['mobile'],
                                'password' => $password,
                            ),
                        );
                        $expire = time() - 30*60;
                        $userCode -> where('reg_time', '<', $expire) -> delete();
                        return urldecode(json_encode($data));        //返回json格式数据(success)
                    }else {         //如果插入失败,则执行
                        $data = array(
                            'result' => 'error',
                            'value' => urlencode('注册失败,请稍后重试'),
                        );
                        return urldecode(json_encode($data));        //返回json格式数据(error)
                    }
                }

            }else {     //如果提交的手机号和验证码不正确,则执行
                $data = array(
                    'result' => 'error',
                    'value' => urlencode('您输入的验证码不正确或已过期'),
                );
                return urldecode(json_encode($data));        //返回json格式数据(error)
            }
        }else {
            $data = array(
                'result' => 'error',
                'value' => urlencode('提交的信息有误'),
            );
            return urldecode(json_encode($data));        //返回json格式数据(error)
        }
    }
    
    public function logon($username, $password) {
        $param = $this->request->param();
        if(isset($param['username']) && $param['username'] != '') {       //判断是否存在POST提交值
            /*if (!empty($param['client_type'])) {      暂不启用客户端类型
                $client_type = $param['client_type'];
            }else {
                return json(array('result'=>'error', 'value'=>'请传入客户端类型'));
                exit();
            }*/
            $name = trim($param['username']);
            $users = db('users');
            $userToken = db('user_token');
            if(preg_match('/^\d{11}$/', $name)){        // 判断是不是11位数字,如果是则往下执行
                $arr = $users -> where('userPhone', $name) -> find();
                if ($arr) {
                    $password       =	$arr['loginPwd'];
                    $mobile         =   $arr['userPhone'];
                    $member_id      =   $arr['userId'];                                  //用户id
                    $member_name    =   $arr['loginName'];                             //用户名
                    $member_avatar  =   $arr['userPhoto'];                           //用户头像
                    $token          =   getToken($member_id, $member_name, $member_avatar);        //获取融云token
                    $login_time = time();
                    $limitTime = time() - 7*24*3600;
                }else {
                    return json(array('result' => 'error', 'value' => '不存在该用户'));
                }

                /*验证用户名密码,如果正确则写入数据库并获取返回token_id和token值*/
                if($param['username'] == $mobile && $password == $param['password']) {        //判断提交用户名\密码是否和数据库密码是否一致
                    //$userToken -> where('login_time', '<', $limitTime) -> where('member_id', $member_id) -> delete();
                    $userToken -> where('member_id', $member_id) -> where('member_name', $member_name) -> delete();
                    $values = array(
                        'member_id' => $member_id,
                        'member_name' => $member_name,
                        'token' => $token,
                        'login_time' => $login_time,
                        //'client_type' => $client_type
                    );
                    if ($token_id = $userToken -> insertGetId($values)) {         //判断如果插入数据库成功
                        //$arr3 = $userToken -> where('member_id', $member_id) -> where('token', $token) -> find();
                        $data = array(
                            'result'    =>  'success',
                            'value'     =>  array(
                                //'token_id' => $arr3['token_id'],        //token_id值
                                'token_id' => $token_id,        //token_id值
                                'token' => $token,      //token值
                                'member_name' => $member_name        //用户登录名
                            ),
                        );
                        return json($data);        //返回json数据(success)
                    }
                }else{          //如果用户名密码不一致,则往下执行
                    $data = array(
                        'result' => 'error',
                        'value' => '手机号或密码不正确',
                    );
                    return json($data);        //返回json数据(error)
                }
            }else{          //如果用户名不是11位数字(手机号)
                $arr = $users -> where('loginName', $name) -> find();
                if ($arr) {
                    $password       =	$arr['loginPwd'];
                    $mobile         =   $arr['userPhone'];
                    $member_name    =   $arr['loginName'];
                    $member_id      =   $arr['userId'];
                    $member_avatar  =   $arr['userPhoto'];                             //用户头像
                    $token          =   getToken($member_id, $member_name, $member_avatar);        //获取融云token
                    $login_time     =   time();
                    $limitTime      =   time() - 7*24*3600;
                }else {
                    return json(array('result' => 'error', 'value' => '不存在该用户'));
                }

                //判断提交值密码 和数据库密码是否一致
                if($name == $param['username'] && $password == $param['password']){
                    //$userToken -> where('login_time', '<', $limitTime) -> where('member_id', $member_id) -> delete();
                    $userToken -> where('member_id', $member_id) -> where('member_name', $member_name) -> delete();
                    $values = array(
                        'member_id' => $member_id,
                        'member_name' => $member_name,
                        'token' => $token,
                        'login_time' => $login_time,
                        //'client_type' => $client_type
                    );
                    if ($token_id = $userToken -> insertGetId($values)) {
                        //$arr3 = $userToken -> where('member_id', $member_id) -> where('token', $token) -> find();
                        $data = array(
                            'result' => 'success',
                            'value' => array(
                                //'token_id' => $arr3['token_id'],
                                'token_id' => $token_id,
                                'token' => $token,
                                'member_name' => $member_name
                            ),
                        );
                        return json($data);
                    }
                }else{
                    $data = array(
                        'result' => 'error',
                        'value' => '用户名或者密码不正确',
                    );
                    return json($data);
                }
            }
        }else{      //不存在POST提交值返回
            $data = array(
                'result' => 'error',
            );
            return json($data);
        }
    }

    //检查token是否有效的函数
    public function checkToken($token_id, $token) {
        $userToken = db('user_token');
        $arr = $userToken -> where('token_id', $token_id) -> where('token', $token) -> find();
        if ($arr) {
            if ((time() - intval($arr['login_time'])) > 7*24*3600) {
                return 'invalid';
            }else {
                return 'success';
            }
        }else {
            return 'error';
        }
    }

    public function userInfo() {
        $param = $this->request->param();
        if (isset($param['token_id'])) {
            $param['token'] = str_replace('\/', '/', $param['token']);
            switch ($this->checkToken($param['token_id'], $param['token'])) {          //验证token是否正确
                case 'success' :
                    return json($this -> getUserInfo($param['token_id'], $param['token']));
                    break;
                case 'invalid' :
                    return json(array('result' => 'invalid', 'value' => '免登陆时间已过,请重新登录'));
                    break;
                case 'error' :
                    return json(array('result' => 'error', 'value' => '免登陆验证失败,请重新登录'));
                    break;
            }
        }
    }

    //查询用户信息的函数
    private function getUserInfo($token_id, $token) {
        $users = db('users a');
        $data = array('a.userId', 'a.loginName', 'a.trueName', 'a.userPhoto','a.userSex', 'a.brithday', 'a.userEmail', 'a.friendID', 'a.userNation', 'a.userProvince', 'a.userCity');
        $arr = $users -> join('user_token b', 'b.member_id = a.userId', 'LEFT') -> where('b.token_id', $token_id) -> where('b.token', $token)->field($data) -> find();
        $encodeArr = array();
        foreach ($arr as $k => $v) {
            if ($k == 'friendID') {
                $encodeArr[$k] = json_decode($v);
            }else {
                $encodeArr[$k] = $v;             //对中文进行url编码
            }

        }
        return $encodeArr;      //返回url解码后的json格式数据
    }

    //文件下载接口
    public function download() {
        $param = $this->request->param();
        $file_name = $param['file_name'];       //文件名
        $file_dir = $param['file_dir'];         //文件地址(不包括文件名)
        $file=fopen($file_dir.$file_name,"r");//下载文件必须先要将文件打开，写入内存
        if(file_exists($file)){//判断文件是否存在
            echo "文件不存在";
            exit();
        }
        $file_size=filesize($file_name);//判断文件大小

        //返回的文件
        Header("Content-type: application/octet-stream");
        //按照字节格式返回
        Header("Accept-Ranges: bytes");
        //返回文件大小
        Header("Content-Length: ".$file_size);
        //弹出客户端对话框，对应的文件名
        Header("Content-Disposition: attachment; filename=".$file_name);
        //防止服务器瞬时压力增大，分段读取
        $buffer=1024;
        while(!feof($file)){
            $file_data=fread($file,$buffer);
            echo $file_data;
        }
        //关闭文件
        fclose($file);
    }



}