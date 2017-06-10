<?php
namespace  wstmart\vvoff\Controller;

use think\Db;
use think\Controller;
use wstmart\chat\controller\Api;

class  Login  extends Controller {

    public $api;
    public $request;

    public function __construct() {
        $this->request = request();
        $this->api = new Api();
    }


    public function send() {
        $mobile=$_POST['mobile'];
        $arr2 = Db::table('jingo_users')->field(['userId', 'loginName', 'userPhoto'])->where('userPhone',$mobile)->find();        //查询手机号相关用户
        if ($arr2) {
            exit();
        }
        $param = $this->request->param();
        if (!empty($param)) {
            $info = $this->api->send($param['mobile']);
            return $info;
        }
    }

    public function sends() {
        $param = $this->request->param();
        if (!empty($param)) {
            $info = $this->api->send($param['mobile']);
            return $info;
        }
    }

    public function reg() {
        $param = $this->request->param();
        if (!empty($param)) {
            $info = $this->api->reg($param['mobile'], $param['code']);

            if (json_decode($info) -> result == 'success') {
                $value = Db::table('jingo_users') -> field(['userName', 'userId']) -> where('userPhone', $param['mobile']) -> find();
                session('WST_USER.userName',$value['userName']);
                session('WST_USER.userId',$value['userId']);
                return view('jump');
            }else {
                $this -> error('注册失败,请稍后重试');
            }
        }
    }
   /* public function alreadyLogin(){
        if(session('WST_USER.userName')!=='' && session('WST_USER.userId')!=='' ){
            return view('')
        }
    }*/


    public function regs()
    {
        $mobile=$_POST['mobile'];
        $arr2 = Db::table('jingo_users')->field(['userId', 'loginName', 'userPhoto'])->where('userPhone',$mobile)->find();        //查询手机号相关用户
        if ($arr2) {
            $data = array(
                'result' => 'repeat',
                'value' => '您的手机号码已注册，请直接登录',
            );
            return $data['value'];
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
                    $loginSecret    =   $arr['loginSecret'];
                    $param['password'] = md5($param['password'].$loginSecret);
                    $login_time = time();
                    $limitTime = time() - 7*24*3600;
                }else {
                    return urldecode(json_encode(array('result' => 'error', 'value' => urlencode('不存在该用户'))));
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
                                'member_name' => urlencode($member_name),        //用户登录名
                                'member_id' => $member_id
                            ),
                        );
                        return urldecode(json_encode($data));        //返回json数据(success)
                    }
                }else{          //如果用户名密码不一致,则往下执行
                    $data = array(
                        'result' => 'error',
                        'value' => urlencode('手机号或密码不正确'),
                    );
                    return urldecode(json_encode($data));        //返回json数据(error)
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
                    $loginSecret    =   $arr['loginSecret'];
                    $param['password'] = md5($param['password'].$loginSecret);
                    $login_time     =   time();
                    $limitTime      =   time() - 7*24*3600;
                }else {
                    return urldecode(json_encode(array('result' => 'error', 'value' => urlencode('不存在该用户'))));
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
                                'member_name' => urlencode($member_name),
                                'member_id' => $member_id
                            ),
                        );
                        return urldecode(json_encode($data));
                    }
                }else{
                    $data = array(
                        'result' => 'error',
                        'value' => urlencode('用户名或者密码不正确'),
                    );
                    return urldecode(json_encode($data));
                }
            }
        }else{      //不存在POST提交值返回
            $data = array(
                'result' => 'error',
            );
            return urldecode(json_encode($data));
        }
    }

    public function cnlogon() {
        $param = $this->request->param();
        if (!empty($param)) {
            $info = $this->logon($param['username'], $param['password']);
            if (!empty($info) && json_decode($info) -> result == 'success') {
                $value = json_decode($info) -> value;
                session('WST_USER.userName',$value -> member_name);
                session('WST_USER.userId',$value -> member_id);
                //$this -> redirect('vvoff/index/index');
                return view('jump');
            }else {
                $this -> error('登录失败,请稍后重试');
            }
        }
    }

    public function engLogon() {
        $param = $this->request->param();
        if (!empty($param)) {
            $info = $this->logon($param['username'], $param['password']);
            if (!empty($info) && json_decode($info) -> result == 'success') {
                $value = json_decode($info) -> value;
                session('WST_USER.userName',$value -> member_name);
                session('WST_USER.userId',$value -> member_id);
                return view('engjump');
                //$this -> redirect('vvoff/index/english');
            }else {
                $this -> error('登录失败,请稍后重试');
            }
        }
    }

    public function quit() {
        if (session('WST_USER.userName')) {
            session('WST_USER.userName', null);
            session('WST_USER.userId', null);
        }
        $this -> redirect('vvoff/index/index');
    }

    public function enquit() {
        if (session('WST_USER.userName')) {
            session('WST_USER.userName', null);
            session('WST_USER.userId', null);
        }
        $this -> redirect('vvoff/index/english');
    }

}
