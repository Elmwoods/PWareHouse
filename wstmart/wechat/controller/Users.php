<?php
namespace wstmart\wechat\controller;
use wstmart\wechat\model\Users as M;
use wstmart\wechat\model\Messages;
use wstmart\common\model\LogSms;
use wstmart\common\model\Users as MUsers;
/**
 * ============================================================================
 * WSTMart多用户商城
 * 版权所有 2016-2066 广州商淘信息科技有限公司，并保留所有权利。
 * 官网地址:http://www.wstmart.net
 * 交流社区:http://bbs.shangtaosoft.com
 * 联系QQ:153289970
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！未经本公司授权您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 用户控制器
 */
class Users extends Base{
	// 前置方法执行列表
    protected $beforeActionList = [
        'checkAuth' =>  ['except'=>'checklogin,login,register,getverify,getphoneverifycode']// 访问这些except下的方法不需要执行前置操作
    ];
    /**
     * 会员登录页
     */
    public function login(){
    	//如果已经登录了则直接跳去用户中心
    	$USER = session('WST_USER');
    	if(!empty($USER) && $USER['userId']!=''){
    		$this->redirect("users/index");
    	}
    	$userinfo = session('WST_WX_USERINFO');
    	$this->assign('info',$userinfo);
    	return $this->fetch('login');
    }
    /**
     * 会员登录
     */
    public function checkLogin(){
    	$m = new M();
    	$rs =  $m->checkLogin(1);
    	$rs['url'] = session('WST_WX_WlADDRESS');
    	return $rs;
    }
    /**
     * 会员注册
     */
    public function register(){
    	$m = new M();
    	$rs =  $m->regist(1);
    	$rs['url'] = session('WST_WX_WlADDRESS');
    	return $rs;
    }
    /**
     * 获取验证码
     */
    public function getPhoneVerifyCode(){
    	$userPhone = input("post.userPhone");
    	$rs = array();
    	if(!WSTIsPhone($userPhone)){
    		return WSTReturn("手机号格式不正确!");
    		exit();
    	}
    	$m = new M();
    	$rs = $m->checkUserPhone($userPhone,(int)session('WST_USER.userId'));
    	if($rs["status"]!=1){
    		return WSTReturn("手机号已存在!");
    		exit();
    	}
    	$phoneVerify = rand(100000,999999);
    	$tpl = WSTMsgTemplates('PHONE_USER_REGISTER_VERFIY');
    	if($tpl['tplContent']!=''){
    		$params = ['tpl'=>$tpl,'params'=>['MALL_NAME'=>WSTConf("CONF.mallName"),'VERFIY_CODE'=>$phoneVerify,'VERFIY_TIME'=>10]];
    		$m = new LogSms();
    		$rv = $m->sendSMS(0,$userPhone,$params,'getPhoneVerifyCode',$phoneVerify);
    	}
    	if($rv['status']==1){
    		session('VerifyCode_userPhone',$phoneVerify);
    		session('VerifyCode_userPhone_Time',time());
    	}
    	return $rv;
    }
	/**
	 * 会员中心
	 */
	public function index(){
		$userId = session('WST_USER.userId');
		$m = new M();
		$user = $m->getById($userId);
		if($user['userName']=='')$user['userName']=$user['loginName'];
		$this->assign('user', $user);
		//商城未读消息的数量 及 各订单状态数量
		$data = model('index')->getSysMsg('msg','order');
		$this->assign('data',$data);
		return $this->fetch('users/index');
	}

	/**
	 * 个人信息
	 */
	public function edit(){
		$userId = session('WST_USER.userId');
		$m = new M();
		$user = $m->getById($userId);
		$this->assign('user', $user);
		return $this->fetch('users/edit');
	}
	/**
	 * 编辑个人信息
	 */
	public function editUserInfo(){
    	$m = new M();
    	return $m->edit();
	}
	/**
	 * 账户安全
	 */
	public function security(){
		$m = new M();
		$userId = (int)session('WST_USER.userId');
		$user = $m->getById($userId);
		$payPwd = $user['payPwd'];
		$userPhone = $user['userPhone'];
		$user['payPwd'] = empty($payPwd)?0:1;
		$user['userPhone'] = empty($userPhone)?0:1;
		$this->assign('user', $user);
		session('Edit_userPhone_Time', null);
		return $this->fetch('users/security/index');
	}
	/**
	 * 修改密码
	 */
	public function editPayPass(){
		$m = new M();
		$userId = (int)session('WST_USER.userId');
		$user = $m->getById($userId);
		$payPwd = $user['payPwd'];
		$user['payPwd'] = empty($payPwd)?0:1;
		$this->assign('user', $user);
		return $this->fetch('users/security/user_pay_pass');
	}
	public function editpayPwd(){
		$m = new M();
		$userId = (int)session('WST_USER.userId');
		return $m->editPayPass($userId);
	}
	/**
	 * 修改手机
	 */
	public function editPhone(){
		$m = new M();
		$userId = (int)session('WST_USER.userId');
		$user = $m->getById($userId);
		$userPhone = $user['userPhone'];
		$user['userPhone'] = WSTStrReplace($user['userPhone'],'*',3);
		$user['phoneType'] = empty($userPhone)?0:1;
		$this->assign('user', $user);
		session('Edit_userPhone_Time', null);
		return $this->fetch('users/security/user_phone');
	}
	/**
	 * 绑定手机：发送短信验证码
	 */
	public function sendCodeTie(){
		$userPhone = input("post.userPhone");
        if(!WSTIsPhone($userPhone)){
            return WSTReturn("手机号格式不正确!");
            exit();
        }
        $rs = array();
        $m = new MUsers();
        $rs = WSTCheckLoginKey($userPhone,(int)session('WST_USER.userId'));
        if($rs["status"]!=1){
            return WSTReturn("手机号已存在!");
            exit();
        }
        $data = $m->getById(session('WST_USER.userId'));
        $phoneVerify = rand(100000,999999);
        $rv = ['status'=>-1,'msg'=>'短信发送失败'];
        $tpl = WSTMsgTemplates('PHONE_BIND');
        if($tpl['tplContent']!=''){
            $params = ['tpl'=>$tpl,'params'=>['LOGIN_NAME'=>$data['loginName'],'VERFIY_CODE'=>$phoneVerify,'VERFIY_TIME'=>10]];
            $m = new LogSms();
            $rv = $m->sendSMS(0,$userPhone,$params,'sendCodeTie',$phoneVerify);
        }
        if($rv['status']==1){
            $USER = [];
            $USER['userPhone'] = $userPhone;
            $USER['phoneVerify'] = $phoneVerify;
            session('Verify_info',$USER);
            session('Verify_userPhone_Time',time());
            return WSTReturn('短信发送成功!',1);
        }
        return $rv;
	}
	/**
	 * 绑定手机
	 */
	public function phoneEdit(){
		$phoneVerify = input("post.phoneCode");
		$timeVerify = session('Verify_userPhone_Time');
		if(!session('Verify_info.phoneVerify') || time()>floatval($timeVerify)+10*60){
			return WSTReturn("校验码已失效，请重新发送！");
			exit();
		}
		if($phoneVerify==session('Verify_info.phoneVerify')){
			$m = new M();
			$rs = $m->editPhone((int)session('WST_USER.userId'),session('Verify_info.userPhone'));
			return $rs;
		}
		return WSTReturn("校验码不一致，请重新输入！");
	}
	/**
	 * 修改手机：发送短信验证码
	 */
	public function sendCodeEdit(){
    	$m = new MUsers();
        $data = $m->getById(session('WST_USER.userId'));
        $userPhone = $data['userPhone'];
        $phoneVerify = rand(100000,999999);
        $rv = ['status'=>-1,'msg'=>'短信发送失败'];
        $tpl = WSTMsgTemplates('PHONE_EDIT');
        if($tpl['tplContent']!=''){
            $params = ['tpl'=>$tpl,'params'=>['LOGIN_NAME'=>$data['loginName'],'VERFIY_CODE'=>$phoneVerify,'VERFIY_TIME'=>10]];
            $m = new LogSms();
            $rv = $m->sendSMS(0,$userPhone,$params,'getPhoneVerifyt',$phoneVerify);
        }
        if($rv['status']==1){
            $USER = [];
            $USER['userPhone'] = $userPhone;
            $USER['phoneVerify'] = $phoneVerify;
            session('Verify_info2',$USER);
            session('Verify_userPhone_Time2',time());
            return WSTReturn('短信发送成功!',1);
        }
        return $rv;
	}
	/**
	 * 修改手机
	 */
	public function phoneEdito(){
		$phoneVerify = input("post.phoneCode");
		$timeVerify = session('Verify_userPhone_Time2');
		if(!session('Verify_info2.phoneVerify') || time()>floatval($timeVerify)+10*60){
			return WSTReturn("校验码已失效，请重新发送！");
			exit();
		}
		if($phoneVerify==session('Verify_info2.phoneVerify')){
			session('Edit_userPhone_Time',time());
			return WSTReturn("验证成功",1);
			return $rs;
		}
		return WSTReturn("校验码不一致，请重新输入！");
	}
	public function editPhoneo(){
		$m = new M();
		$userId = (int)session('WST_USER.userId');
		$user = $m->getById($userId);
		$userPhone = $user['userPhone'];
		$user['userPhone'] = WSTStrReplace($user['userPhone'],'*',3);
		$timeVerify = session('Edit_userPhone_Time');
		if(time()>floatval($timeVerify)+15*60){
			$user['phoneType'] = 1;
		}else{
			$user['phoneType'] = 0;
		}
		$this->assign('user', $user);
		return $this->fetch('users/security/user_phone');
	}
}
