<?php
namespace wstmart\wechat\model;
use wstmart\common\model\Users as CUsers;
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
class Users extends CUsers{
	/**
	 * 用户自动登录
	 */
	public function accordLogin(){
		$wxOpenId = session('WST_WX_OPENID');
		$rs = $this->where(["dataFlag"=>1, "userStatus"=>1,"wxOpenId"=>$wxOpenId])->order('lastTime desc')->find();
		if(!empty($rs)){
			$userId = $rs['userId'];
			//获取用户等级
			$rrs = Db::name('user_ranks')->where('startScore','<=',$rs['userTotalScore'])->where('endScore','>=',$rs['userTotalScore'])->field('rankId,rankName,rebate,userrankImg')->find();
			$rs['rankId'] = $rrs['rankId'];
			$rs['rankName'] = $rrs['rankName'];
			$rs['userrankImg'] = $rrs['userrankImg'];
			$rs['wxOpenId'] = session('WST_WX_OPENID');
			$ip = request()->ip();
			$update = [];
			$update = ["lastTime"=>date('Y-m-d H:i:s'),"lastIP"=>$ip];
			$update['wxOpenId'] = session('WST_WX_OPENID');
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
			$data['loginSrc'] = 1;
			Db::name('log_user_logins')->insert($data);
			session('WST_USER',$rs);
			return WSTReturn("","1");
		}
		return WSTReturn("用户不存在");
	}
	/**
	* 验证用户支付密码
	*/ 
	function checkPayPwd(){
		$userId = (int)session('WST_USER.userId');
		$rs = $this->field('payPwd,loginSecret')->find($userId);
		$payPwd = input('payPwd');
		if($rs['payPwd']==md5($payPwd.$rs['loginSecret'])){
			return WSTReturn('',1);
		}
		return WSTReturn('支付密码错误',-1);
	}
}
