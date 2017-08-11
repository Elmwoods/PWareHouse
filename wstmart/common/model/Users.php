<?php
namespace wstmart\common\model;
use Think\Db;
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
 * 用户类
 */
class Users extends Base{
    /**
     * 用户登录验证
     */
    public function checkLogin($loginSrc = 0){
        $loginName = input("post.loginName");  //用户名
        $phoneName = input("post.loginName1"); //手机号
        $loginPwd = input("post.loginPwd");    //密码
        $mobileCode=input('post.mobileCode');  //手机校验码
        $code = input("post.verifyCode");      //验证码
        $rememberPwd = input("post.rememberPwd",1); //记住密码

        if(!WSTVerifyCheck($code) && strpos(WSTConf("CONF.captcha_model"),"4")>=0){
            return WSTReturn('验证码错误!');
        }

        //添加代码 start
        if($loginPwd != ''){
            $rs = $this->where("loginName|userPhone",$loginName)
                    ->where(["dataFlag"=>1, "userStatus"=>1])
                    ->find();
            if($rs){
                if($rs['loginPwd']!=md5($loginPwd.$rs['loginSecret']))return WSTReturn("密码错误");
            }else{
                return WSTReturn("用户名或密码错误"); 
            }
             
        }else{
            $rs = $this->where("loginName|userPhone",$phoneName)
                    ->where(["dataFlag"=>1, "userStatus"=>1])
                    ->find();
            if($rs){
                $model=Db::name('user_code');
                $id=$model->where('mobile',$phoneName)->max('id');
                $model->where('mobile',$phoneName)->where('id','lt',$id)->delete();
                $model->where('mobile',$phoneName)->where('reg_time','lt',time()-30*60)->delete();
                if (!$model->where('mobile',$phoneName)->where('code',$mobileCode)->find()) {
                    return WSTReturn("短信验证码错误"); 
                }   
            }else{
                return WSTReturn("该手机号未注册或未绑定"); 
            }
        }
        //添加代码 end
        
        hook("beforeUserLogin",["user"=>&$rs]);
        if(!empty($rs)){

            if($rs['userPhoto']=='')$rs['userPhoto'] = WSTConf('CONF.userLogo');
            $userId = $rs['userId'];
            //获取用户等级
            $rrs = Db::name('user_ranks')->where(['dataFlag'=>1])->where('startScore','<=',$rs['userTotalScore'])->where('endScore','>=',$rs['userTotalScore'])->field('rankId,rankName,rebate,userrankImg')->find();
            $rs['rankId'] = $rrs['rankId'];
            $rs['rankName'] = $rrs['rankName'];
            $rs['userrankImg'] = $rrs['userrankImg'];
            if(input("post.typ")==2){
                $shoprs=$this->where(["dataFlag"=>1, "userStatus"=>1,"userType"=>1,"userId"=>$userId])->find();
                if(empty($shoprs)){
                    return WSTReturn('您还没申请店铺!');
                }
            }
            $ip = request()->ip();
            $update = [];
            $update = ["lastTime"=>date('Y-m-d H:i:s'),"lastIP"=>$ip];
            $wxOpenId = session('WST_WX_OPENID');
            if($wxOpenId){
                $update['wxOpenId'] = $rs['wxOpenId'] = session('WST_WX_OPENID');
            }
            $this->where(["userId"=>$userId])->update($update);
            //如果是店铺则加载店铺信息
            if($rs['userType']>=1){
                $shop = model('shops')->where(["userId"=>$userId,"dataFlag" =>1])->find();
                if(!empty($shop))$rs = array_merge($shop->toArray(),$rs->toArray());
            }
            //记录登录日志
            $data = array();
            $data["userId"] = $userId;
            $data["loginTime"] = date('Y-m-d H:i:s');
            $data["loginIp"] = $ip;
            $data['loginSrc'] = $loginSrc;
            Db::name('log_user_logins')->insert($data);
            $rd = $rs;
            //记住密码
            cookie("loginName", $loginName, time()+3600*24*90);
            if($rememberPwd == "on"){
                $datakey = md5($rs['loginName'])."_".md5($rs['loginPwd']);
                $key = $rs['loginSecret'];
                //加密
                $base64 = new \org\Base64();
                $loginKey = $base64->encrypt($datakey, $key);
                cookie("loginPwd", $loginKey, time()+3600*24*90);
            }else{
                cookie("loginPwd", null);
            }
            session('WST_USER',$rs);
            
            hook('afterUserLogin',['user'=>$rs]);
            
            return WSTReturn("登录成功","1");
        
        }
        return WSTReturn("用户不存在");
    }
    
    /**
     * 会员注册
     */
    public function regist($loginSrc = 0){
    	
    	$data = array();
    	$data['loginName'] = input("post.loginName");
    	$data['loginPwd'] = input("post.loginPwd");
    	$data['reUserPwd'] = input("post.reUserPwd");
    	$loginName = $data['loginName'];

        //添加代码 start
        //不能使用邮箱注册  
        $preg = "/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/";
        if(preg_match($preg,$loginName)){
            return WSTReturn("请使用 用户名或手机号注册!");
        }
        //添加代码 end
        
    	//检测账号是否存在
    	$crs = WSTCheckLoginKey($loginName);
    	if($crs['status']!=1)return $crs;
    	if($data['loginPwd']!=$data['reUserPwd']){
    		return WSTReturn("两次输入密码不一致!");
    	}
    	foreach ($data as $v){
    		if($v ==''){
    			return WSTReturn("注册信息不完整!");
    		}
    	}
    	$nameType = (int)input("post.nameType");
    	$mobileCode = input("post.mobileCode");
    	$code = input("post.verifyCode");
    	if($nameType!=3 && !WSTVerifyCheck($code)){
    		return WSTReturn("验证码错误!");
    	}
    	if($nameType==3 && WSTConf("CONF.smsOpen")==1){//手机号码
    		$data['userPhone'] = $loginName;
    		$verify = session('VerifyCode_userPhone');
    		$startTime = (int)session('VerifyCode_userPhone_Time');
    		if((time()-$startTime)>120){
    			return WSTReturn("验证码已超过有效期!");
    		}
    		if($mobileCode=="" || $verify != $mobileCode){
    			return WSTReturn("验证码错误!");
    		}
    		$loginName = WSTRandomLoginName($loginName);
    	}else if($nameType==1){//邮箱注册
    		$data['userEmail'] = $loginName;
    		$unames = explode("@",$loginName);
    		$loginName = WSTRandomLoginName($unames[0]);
    		
    	}
    	if($loginName=='')return WSTReturn("注册失败!");//分派不了登录名
    	$data['loginName'] = $loginName;
    	unset($data['reUserPwd']);
    	unset($data['protocol']);
    	//检测账号，邮箱，手机是否存在
        //添加代码 start
        $maxId = Db::name('users')->max('userId');
        $flag = ($maxId+1)%100 ;
        $a =(string)floor(($maxId+1)/100) ;
        if ($flag == 0) { //如果 userId 是 100 的倍数  则加一个客服
            $data1['loginName'] = 'server'.$a;
            $data1["loginSecret"] = rand(1000,9999);
            $data1['loginPwd'] = md5('123456789'.$data1['loginSecret']);
            $data1['userType'] = 0;
            $data1['userName'] = '客服'.$a;
            $data1['userQQ'] = "";
            $data1['userScore'] = 0;
            $data1['createTime'] = date('Y-m-d H:i:s');
            $data1['dataFlag'] = 1;
            $data1['userSex'] = 1;
            Db::name('users')->insert($data1);
        }
        //添加代码 end

    	$data["loginSecret"] = rand(1000,9999);
    	$data['loginPwd'] = md5($data['loginPwd'].$data['loginSecret']);
    	$data['userType'] = 0;
    	$data['userName'] = input("post.userName");
    	$data['userQQ'] = "";
    	$data['userScore'] = 0;
    	$data['createTime'] = date('Y-m-d H:i:s');
    	$data['dataFlag'] = 1;
        $wxOpenId = session('WST_WX_OPENID');
    	if($wxOpenId){
    		$data['wxOpenId'] = session('WST_WX_OPENID');
			$userinfo = session('WST_WX_USERINFO');
			if($userinfo){
				$data['userName'] = $userinfo['nickname'];
				$data['userSex'] = $userinfo['sex'];
				$data['userPhoto'] = $userinfo['headimgurl'];
			}
    	}
        $data['userSex'] = 1;//添加代码 
    	Db::startTrans();
        try{
	    	$userId = $this->data($data)->save();
	    	if(false !== $userId){

	    		$data = array();
	    		$ip = request()->ip();
	    		$data['lastTime'] = date('Y-m-d H:i:s');
	    		$data['lastIP'] = $ip;
	    		$userId = $this->userId;
	    		$this->where(["userId"=>$userId])->update($data);
	    		//记录登录日志
	    		$data = array();
	    		$data["userId"] = $userId;
	    		$data["loginTime"] = date('Y-m-d H:i:s');
	    		$data["loginIp"] = $ip;
                $data['loginSrc'] = $loginSrc;
	    		Db::name('log_user_logins')->insert($data);
	    		$user = $this->get($userId);
	    	    if($user['userPhoto']=='')$user['userPhoto'] = WSTConf('CONF.userLogo');
	    		session('WST_USER',$user);
	    		//注册成功后执行钩子
	    		hook('afterUserRegist',['user'=>$user]);
                //发送消息
                $tpl = WSTMsgTemplates('USER_REGISTER');
                if($tpl['tplContent']!=''){
                    $find = ['${LOGIN_NAME}','${MALL_NAME}'];
                    $replace = [$user['loginName'],WSTConf('CONF.mallName')];
                    WSTSendMsg($userId,str_replace($find,$replace,$tpl['tplContent']),['from'=>0,'dataId'=>0]);
                }
	    		Db::commit();
	    		return WSTReturn("注册成功",1);
	    	}
        }catch (\Exception $e) {
        	Db::rollback();
        }
    	return WSTReturn("注册失败!");
    }
    
    /**
     * 查询用户手机是否存在
     * 
     */
    public function checkUserPhone($userPhone,$userId = 0){
    	$dbo = $this->where(["dataFlag"=>1, "userPhone"=>$userPhone]);
    	if($userId>0){
    		$dbo->where("userId","<>",$userId);
    	}
    	$rs = $dbo->count();
    	if($rs>0){
    		return WSTReturn("手机号已存在!");
    	}else{
    		return WSTReturn("",1);
    	}
    }

    /**
     * 修改用户密码  原代码
     */
    // public function editPass($id){
    // 	$data = array();
    // 	$data["loginPwd"] = input("post.newPass");
    // 	if(!$data["loginPwd"]){
    // 		return WSTReturn('密码不能为空',-1);
    // 	}
    // 	$rs = $this->where('userId='.$id)->find();
    // 	//核对密码
    // 	if($rs['loginPwd']){
    // 		if($rs['loginPwd']==md5(input("post.oldPass").$rs['loginSecret'])){
    // 			$data["loginPwd"] = md5(input("post.newPass").$rs['loginSecret']);
    // 			$rs = $this->update($data,['userId'=>$id]);
    // 			if(false !== $rs){
    // 				hook("afterEditPass",["userId"=>$id]);
    // 				return WSTReturn("密码修改成功", 1);
    // 			}else{
    // 				return WSTReturn($this->getError(),-1);
    // 			}
    // 		}else{
    // 			return WSTReturn('原始密码错误',-1);
    // 		}
    // 	}else{
    // 		$data["loginPwd"] = md5(input("post.newPass").$rs['loginSecret']);
    // 		$rs = $this->update($data,['userId'=>$id]);
    // 		if(false !== $rs){
    // 			hook("afterEditPass",["userId"=>$id]);
    // 			return WSTReturn("密码修改成功", 1);
    // 		}else{
    // 			return WSTReturn($this->getError(),-1);
    // 		}
    // 	}
    // }
    /**
     * 修改用户支付密码
     */
    public function editPayPass($id){
        $data = array();
        $data["payPwd"] = input("post.newPass");
        if(!$data["payPwd"]){
            return WSTReturn('支付密码不能为空',-1);
        }
        $rs = $this->where('userId='.$id)->find();
        //核对密码
        if($rs['payPwd']){
            if($rs['payPwd']==md5(input("post.oldPass").$rs['loginSecret'])){
                $data["payPwd"] = md5($data["payPwd"].$rs['loginSecret']);
                $rs = $this->update($data,['userId'=>$id]);
                if(false !== $rs){
                    return WSTReturn("支付密码修改成功", 1);
                }else{
                    return WSTReturn("支付密码修改失败",-1);
                }
            }else{
                return WSTReturn('原始支付密码错误',-1);
            }
        }else{
            $data["payPwd"] = md5($data["payPwd"].$rs['loginSecret']);
            $rs = $this->update($data,['userId'=>$id]);
            if(false !== $rs){
                return WSTReturn("支付密码设置成功", 1);
            }else{
                return WSTReturn("支付密码修改失败",-1);
            }
        }
    }
   /**
    *  获取用户信息
    */
    public function getById($id){
    	$rs = $this->get(['userId'=>(int)$id]);
    	$rs['ranks'] = Db::name('user_ranks')->where(['dataFlag'=>1])->where('startScore','<=',$rs['userTotalScore'])->where('endScore','>=',$rs['userTotalScore'])->field('rankId,rankName,rebate,userrankImg')->find();
    	return $rs;
    }
    /**
     * 编辑资料
    */
    public function edit(){
    	$Id = (int)session('WST_USER.userId');
    	$data = input('post.');
        if(isset($data['brithday']))$data['brithday'] = ($data['brithday']=='')?date('Y-m-d'):$data['brithday'];
    	WSTAllow($data,'brithday,trueName,userName,userId,userPhoto,userQQ,userSex');
    	Db::startTrans();
		try{
            if(isset($data['userPhoto']) && $data['userPhoto']!='')
			     WSTUseImages(0, $Id, $data['userPhoto'],'users','userPhoto');
             
	    	$result = $this->allowField(true)->save($data,['userId'=>$Id]);
	    	if(false !== $result){
	    		Db::commit();
	    		return WSTReturn("编辑成功", 1);
	    	}
		}catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('编辑失败',-1);
        }	
    }
    /**
    * 绑定邮箱
     */
    public function editEmail($userId,$userEmail){
    	$data = array();
    	$data["userEmail"] = $userEmail;
    	Db::startTrans();
    	try{
    		$user = Db::name('users')->where(["userId"=>$userId])->field(["userId","loginName,userEmail"])->find();
			$rs = $this->update($data,['userId'=>$userId]);
			if(false !== $rs){
				hook("afterEditEmail",["user"=>$user]);
				Db::commit();
				return WSTReturn("绑定成功",1);
			}else{
				Db::rollback();
				return WSTReturn("",-1);
			}
		}catch (\Exception $e) {
    		Db::rollback();
    		return WSTReturn('编辑失败',-1);
    	}
    }
    /**
     * 绑定手机
     */
    public function editPhone($userId,$userPhone){
    	$data = array();
    	$data["userPhone"] = $userPhone;
    	$rs = $this->update($data,['userId'=>$userId]);
    	if(false !== $rs){
    		return WSTReturn("绑定成功", 1);
    	}else{
    		return WSTReturn($this->getError(),-1);
    	}
    }
    /**
     * 查询并加载用户资料
     */
    public function checkAndGetLoginInfo($key){
    	if($key=='')return array();
    	$rs = $this->where(["loginName|userEmail|userPhone"=>['=',$key],'dataFlag'=>1])->find();
    	return $rs;
    }
    /**
     * 重置用户密码
     */
    public function resetPass(){
    	if(time()>floatval(session('REST_Time'))+30*60){
    		return WSTReturn("连接已失效！", -1);
    	}
    	$reset_userId = (int)session('REST_userId');
    	if($reset_userId==0){
    		return WSTReturn("无效的用户！", -1);
    	}
    	$user = $this->where(["dataFlag"=>1,"userStatus"=>1,"userId"=>$reset_userId])->find();
    	if(empty($user)){
    		return WSTReturn("无效的用户！", -1);
    	}
    	$loginPwd = input("post.loginPwd");
    	if(trim($loginPwd)==''){
    		return WSTReturn("无效的密码！", -1);
    	}
    	$data['loginPwd'] = md5($loginPwd.$user["loginSecret"]);
    	$rc = $this->update($data,['userId'=>$reset_userId]);
    	if(false !== $rc){
            session('REST_userId',null);
            session('REST_Time',null);
            session('REST_success',null);
            session('findPass',null);
    		return WSTReturn("修改成功", 1);
    	}
    	return $rs;
    }
    
    /**
     * 获取用户可用积分
     */
    public function getFieldsById($userId,$fields){
    	return $this->where(['userId'=>$userId,'dataFlag'=>1])->field($fields)->find();
    }
    
}
