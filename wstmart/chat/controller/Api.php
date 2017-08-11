<?php
/**
 * Created by PhpStorm.
 * User: Lynn
 * Date: 2017/4/26
 * Time: 9:21
 */

namespace wstmart\chat\controller;



use think\Controller;
use think\Db;
use wstmart\chat\model\Users;

class Api extends Controller {
    public $request;
    private $RongCloud;

    public function __construct() {
        $this -> request = request();
        $this -> RongCloud = new Rongyun();
    }


    public function upload() {
        return view('default/upload');
    }


    //app初始化页面接口(用于验证是否免登陆)
    public function index() {
        $param = $this->request->param();
        if (!empty($param)) {
            $param['token'] = str_replace('\/', '/', $param['token']);
            switch ($this->checkToken($param['token_id'], $param['token'])) {          //验证token是否正确
                case 'success' :
                    return urldecode(json_encode(array('result' => 'success', 'msg' => urlencode('验证通过,允许进入主界面'))));
                    break;
                case 'invalid' :
                    return urldecode(json_encode(array('result' => 'invalid', 'msg' => urlencode('免登陆时间已过,请重新登录'))));
                    break;
                case 'error' :
                    return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('免登陆验证失败,请重新登录'))));
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
                    'msg' => '该手机号已注册',
                );
                return json($data);        //返回json格式数据(error)
            }else {             //如果为假,继续发送短信*/
                $result = $smsOperator -> single_send($data) -> responseData;     //发送短信,并获取返回信息
                $returnCode = $result['code'];          //获取返回码
                switch ($returnCode) {
                    case 2 :
                        $data = array(
                            'result' => 'error',
                            'msg' => urlencode('您确定有这样的手机号吗?'),
                        );
                        return urldecode(json_encode($data));
                        break;
                    case 22 :
                        $data = array(
                            'result' => 'error',
                            'msg' => urlencode('同一手机号1小时内不得发送超过3次'),
                        );
                        return urldecode(json_encode($data));
                        break;
                    case 33 :
                        $data = array(
                            'result' => 'error',
                            'msg' => urlencode('30秒内不得重复发送'),
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
                            'msg' => urlencode('请求超时'),
                        );
                        return urldecode(json_encode($data));
                }
            //}
        }else {
            $data = array(
                'result' => 'error',
                'msg' => urlencode('请输入11位有效手机号'),
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
                    'msg' => urlencode('验证码错误,请重新获取'),
                );
                return urldecode(json_encode($data));
                exit();
            }
            $expire = time() - $arr1['reg_time'];
            if ($arr1['mobile'] == $param['mobile'] && $arr1['code'] == intval($param['code']) && intval($expire) < 30*60) {       //判断提交的手机号和验证码是否与服务器保存的一致并且验证码存在不超过30分钟,如果是则往下执行
                $arr2 = Db::table('jingo_users') ->field(['userId', 'loginName', 'userPhoto', 'userName']) -> where('userPhone', $param['mobile']) -> find();        //查询手机号相关用户
                if ($arr2) {        //如果已注册,则返回token和token_id
                    $token = getToken($arr2['userId'], $arr2['loginName'], $arr2['userPhoto']);
                    $values = array(
                        'token' => $token,
                        'member_id' => $arr2['userId'],
                        'member_name' => $arr2['loginName'],
                        'login_time' => time(),
                    );
                    Db::startTrans();       //事务开始
                    Db::table('jingo_user_token') -> where(['member_id'=>$arr2['userId']]) -> delete();
                    $token_id = Db::table('jingo_user_token') -> insertGetId($values);
                    //$boolen1 = Db::table('jingo_user_code') -> where('mobile', $param['mobile']) -> delete();
                    if ($token_id) {       //删除就有token,新增token,并获取token_id
                        Db::commit();       //事务提交
                        $data = array(
                            'result' => 'success',
                            'value' => array('token_id'=>$token_id, 'token'=>$token, 'state' => 1),
                        );
                        return urldecode(json_encode($data));
                    }else {
                        Db::rollback();         //事务回滚
                        return urldecode(json_encode(['result'=>'error', 'value'=>urlencode('网络又开小差了,请稍后重试')]));
                    }


                    /*$tokenData = Db::table('jingo_user_token') -> field(['token_id', 'token']) -> where('member_id', $arr2['userId']) -> where('member_name', $arr2['loginName']) -> order('login_time', 'DESC') -> find();
                    if ($tokenData) {       //如果存在该用户的token,则返回相关信息
                        $data = array(
                            'result' => 'success',
                            'msg' => $tokenData,
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
                    $loginSecret = mt_rand(1000, 9999);
                    $password_md5 = md5($password.$loginSecret);
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
                    $users -> startTrans();
                    $id = $users -> insertGetId($values);
                    if ($id) {      //如果插入成功,则往下执行
                        if (mb_substr(strval($id), -2) == '00') {       //判断末两位是否为00(凡是末两位为00的均为客服人员)
                            $users -> rollback();                       //数据库回退,让出这个ID
                            $users -> startTrans();
                            //同时插入客服和新注册用户两条数据
                            if ($users -> insert(['userId'=>$id, 'loginName'=>'_server'.mb_substr($id, 0, -2 ), 'userName'=>'客服'.mb_substr($id, 0, -2 ),'loginSecret'=>$loginSecret, 'loginPwd'=>md5('12345678'.$loginSecret), 'userPhoto'=>'upload/userPhotos/userServer.png', 'createTime'=>date('Y-m-d H:i:s', time())]) && $id2 = $users -> insertGetId($values)) {
                                $users -> commit();
                            }else {
                                $users -> rollback();
                                $data = array(
                                    'result' => 'error',
                                    'msg' => urlencode('注册失败,请稍后重试'),
                                );
                                return urldecode(json_encode($data));
                            }
                        }else {
                            $users -> commit();
                        }

                        //成功后返回的数据
                        $data = array(
                            'result' => 'success',
                            'value' => array(
                                'mobile' => $param['mobile'],
                                'password' => $password,
                                'loginSecret' => $loginSecret,
                                'state'=>0,
                            ),
                        );

                        //客服发送一条消息
                        if (mb_substr(strval($id), -2) == '00') {       //判断是否为客服
                            if (isset($id2)) {
                                $this -> RongCloud ->publishPrivates($id, $id2, 'RC:TxtMsg', '{"content":"hello"}', '', '', 1, 1, 1, 1, 1);
                            }
                        }else {
                            $this -> RongCloud ->publishPrivates(mb_substr($id, 0, -2 ).'00', $id, 'RC:TxtMsg', '{"content":"欢迎您使用vv。如果您在使用过程中有任何的问题或建议，记得给我发信或者留言反馈哦~"}', '', '', 1, 1, 1, 1, 1);
                        }

                        $expire = time() - 30*60;
                        $userCode -> where('reg_time', '<', $expire) -> delete();
                        return urldecode(json_encode($data));        //返回json格式数据(success)
                    }else {         //如果插入失败,则执行
                        $data = array(
                            'result' => 'error',
                            'msg' => urlencode('注册失败,请稍后重试'),
                        );
                        return urldecode(json_encode($data));        //返回json格式数据(error)
                    }
                }
            }else {     //如果提交的手机号和验证码不正确,则执行
                $data = array(
                    'result' => 'error',
                    'msg' => urlencode('您输入的验证码不正确或已过期'),
                );
                return urldecode(json_encode($data));        //返回json格式数据(error)
            }
        }else {
            $data = array(
                'result' => 'error',
                'msg' => urlencode('提交的信息有误'),
            );
            return urldecode(json_encode($data));        //返回json格式数据(error)
        }
    }

    //登陆
    public function logon($username, $password) {
        $param = $this->request->param();
        if(isset($param['username']) && $param['username'] != '') {       //判断是否存在POST提交值
            /*if (!empty($param['client_type'])) {      暂不启用客户端类型
                $client_type = $param['client_type'];
            }else {
                return json(array('result'=>'error', 'msg'=>'请传入客户端类型'));
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
                    $member_id      =   $arr['userId'];                                 //用户id
                    $member_name    =   $arr['loginName'];                              //用户名
                    $member_avatar  =   $arr['userPhoto'];                              //用户头像
                    $token          =   getToken($member_id, $member_name, $member_avatar);        //获取融云token
                    $login_time = time();
                    $limitTime = time() - 7*24*3600;
                }else {
                    return json(array('result' => 'error', 'msg' => '不存在该用户'));
                }

                /*验证用户名密码,如果正确则写入数据库并获取返回token_id和token值*/
                if($param['username'] == $mobile && $password == $param['password']) {        //判断提交用户名\密码是否和数据库密码是否一致
                    //$userToken -> where('login_time', '<', $limitTime) -> where('member_id', $member_id) -> delete();
                    $userToken -> where('member_id', $member_id) -> delete();
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
                        'msg' => '手机号或密码不正确',
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
                    return json(array('result' => 'error', 'msg' => '不存在该用户'));
                }

                //判断提交值密码 和数据库密码是否一致
                if($name == $param['username'] && $password == $param['password']){
                    //$userToken -> where('login_time', '<', $limitTime) -> where('member_id', $member_id) -> delete();
                    $userToken -> where('member_id', $member_id) -> delete();
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
                        'msg' => '用户名或者密码不正确',
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

    //个人用户信息
    public function userInfo($token_id, $token) {
        $param = $this->request->param();
        if (isset($param['token_id'])) {
            $param['token'] = str_replace('\/', '/', $param['token']);
            switch ($this->checkToken($param['token_id'], $param['token'])) {          //验证token是否正确
                case 'success' :
                    return json($this -> getUserInfo($param['token_id'], $param['token']));
                    break;
                case 'invalid' :
                    return json(array('result' => 'error', 'msg' => '免登陆时间已过,请重新登录'));
                    break;
                case 'error' :
                    return json(array('result' => 'error', 'msg' => '免登陆验证失败,请重新登录'));
                    break;
                default :
                    return json(array('result' => 'error', 'msg' => '未知错误'));
            }
        }else {
            return json(array('result' => 'error', 'value' => '请传参'));
        }
    }

    //查询用户信息的函数
    private function getUserInfo($token_id, $token) {
        $users = db('users a');
        $data = array('a.userId', 'a.loginName', 'a.trueName', 'a.userPhoto', 'a.userPhone', 'a.userSex', 'a.brithday', 'a.userAge', 'a.signature', 'a.userEmail', 'a.userName', 'a.userNation', 'a.userProvince', 'a.userCity', 'a.setAge', 'a.setDistance', 'a.isValidate', 'a.setStranger', 'a.isRec', 'a.isSeted', 'a.passSeted');
        $arr = $users -> join('user_token b', 'b.member_id = a.userId', 'LEFT') -> where('b.token_id', $token_id) -> where('b.token', $token)->field($data) -> find();
        /*$encodeArr = array();
        foreach ($arr as $k => $v) {
            if ($k == 'friendID') {
                $encodeArr[$k] = json_decode($v);
            }else {
                $encodeArr[$k] = $v;             //对中文进行url编码
            }

        }*/
        return $arr;      //返回url解码后的json格式数据
    }

    //上传文件
    public function uploadFile() {
        $param = $this->request->param();
        //$users = new Users();
//        if (!empty($param)) {
            //获取上传图片
            $file = request()->file('vvFile');
            // 移动到框架应用根目录/public/uploads/ 目录下
            if (!empty($file)) {
                if (!file_exists(ROOT_PATH . 'upload/files/')) {
                    mkdir(ROOT_PATH . 'upload/files/');
                }
                // 移动到框架应用根目录/public/uploads/目录下
                $info = $file->rule('uniqid')->move(ROOT_PATH . 'upload/files/');
                if ($info) {
                    $path = 'upload/files/'.$info->getFilename();
                } else {
                    // 上传失败获取错误信息
                    //echo $file->getError();
                    return json(array('result' => 'error', 'msg' => $file->getError()));
                }
            } else {
                return json(array('result' => 'error', 'msg' => '上传失败'));
            }

//            if (isset($path)) {
//                $param['userBG'] = $path;
//                $pre = $users -> field('userPhoto') -> find($param['userId']);
//            }

//            $users->startTrans();
//            if ($users -> allowField(true) -> isUpdate(true) -> save($param, ['userId'=>$param['userId']])) {
//                if (isset($path) && $pre['userPhoto'] !='' && file_exists(ROOT_PATH.$pre['userPhoto'])) {
//                    unlink($pre['userPhoto']);
//                }
//                $users -> commit();
//                if (isset($path)) {
//                    return json(array('result' => 'success', 'msg' => '已更改设置', 'path'=>$path));
//                }else {
//                    return json(array('result' => 'success', 'msg' => '已更改设置'));
//                }
//            }else {
//                $users -> rollback();
//                return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍后重试'));
//            }
//        }else {
//            return json(['result' => 'error', 'msg' => '请传参']);
//        }
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

    //版本号
    public function version() {
        $arr = Db::table('jingo_version') -> order('time', 'DESC') -> find();
        return json(array('result' => 'success', 'value' => $arr));
    }

    //验证token唯一性
    public function checkUnique($userId, $token) {
        $param = $this->request->param();
        if (!empty($param)) {
            $param['token'] = str_replace('\/', '/', $param['token']);
            $info = Db::table('jingo_user_token') -> field('token') -> where('member_id', intval($param['userId'])) -> find();
            if ($info) {
                if (md5($info['token']) == md5($param['token'])) {
                    return json(['result'=>'success']);
                }else {
                    return json(['result'=>'error']);
                }
            }else {
                return json(['result'=>'error']);
            }

        }
    }

    //用于验证token,防止第三方调用接口
    static public function checkApi($userId, $token) {
        $param['token'] = str_replace('\/', '/', $token);
        $info = Db::table('jingo_user_token') -> field(['token', 'login_time']) -> where('member_id', intval($userId)) -> find();
        if ($info) {
            $expire = time() - 7*24*3600;
            if (($info['login_time'] > $expire) && (md5($info['token']) == md5($token))) {
                return 'true';
            }else {
                return 'false';
            }
        }else {
            return 'false';
        }
    }

    //测试内网连接阿里云数据库MySql版
    public function test() {
        $pdo = new \PDO("mysql:host=rm-bp112h403424k2tvt.mysql.rds.aliyuncs.com;dbname=jingo","holyflaman","Qweasd321");
        $result = $pdo -> query('SELECT userId FROM jingo_users');
        $arr = $result -> fetchAll(2);
        var_dump($arr);
    }

    

}